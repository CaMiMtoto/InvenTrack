<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JournalEntry;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\StockMovement;
use Auth;
use DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index()
    {
        if (\request()->ajax()) {

            $startDate = \request('start_date');
            $endDate = \request('end_date');
            $status = \request('status');

            $data = Order::query()
                ->with('customer')
                ->withCount('items')
                ->withSum('items', DB::raw("quantity * price"))
                ->when($startDate, fn($query, $startDate) => $query->whereDate('order_date', '>=', $startDate))
                ->when($endDate, fn($query, $endDate) => $query->whereDate('order_date', '<=', $endDate))
                ->when($status, fn($query, $status) => $query->where('status', $status));

            return \DataTables::of($data)
                ->addColumn('action', fn(Order $Order) => view('admin.sales.partials.actions', compact('Order')))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::query()
            ->where('stock', '>', 0)
            ->get();
        $paymentMethods = PaymentMethod::query()->get();
        return view('admin.sales.create', compact('customers', 'products', 'paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'], // Ensure supplier exists
            'status' => ['nullable', 'in:pending,completed,canceled'], // Customize statuses as needed
            'order_date' => ['required', 'date'],
            'product_ids' => ['required', 'array'],
            'quantities' => ['required', 'array'],
            'prices' => ['required', 'array'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'amount' => ['nullable', 'numeric', 'min:0'],
        ]);
        try {
            DB::beginTransaction();
            $total_amount = 0;
            // Create the purchase order
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'status' => $data['status'] ?? 'Order',
                'order_date' => $data['order_date'],
                'total_amount' => $total_amount,
                'done_by' => auth()->id()
            ]);

            $order->generateInvoiceNumber();

            // Loop through items and add them to the purchase order

            foreach ($data['product_ids'] as $index => $product_id) {
                $qty = $data['quantities'][$index];
                $price = $data['prices'][$index];
                $orderItem = $order->items()->create([
                    'product_id' => $product_id,
                    'quantity' => $qty,
                    'price' => $price,
                ]);

                $total_amount += $qty * $price;
                // Find and update product stock quantity
                $product = Product::find($product_id);
                $newQty = $qty;

                if ($product) {
                    // Ensure the stock is sufficient (checking in boxes or units)
                    if ($product->stock < $newQty) {
                        DB::rollBack(); // Roll back the transaction
                        return redirect()->back()->withErrors(['error' => 'Insufficient stock for product: ' . $product->name])
                            ->withInput($data); // Return error and retain the form inputs
                    }

                    // Update the product's stock quantity
                    $product->stock -= $newQty; // Decrease by newQty (boxes or units)
                    $product->save(); // Save changes to product

                }
            }

            $order->update(['total_amount' => $total_amount]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback in case of error
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput($data);
        }
        if ($request->ajax()) {
            return response()->json([
                'success' => 'Sales order saved successfully.',
                'url' => route('admin.sale-orders.index')
            ]);
        }

        return redirect()->route('admin.sale-orders.index')->with('success', 'Sales order saved successfully.');
    }

    // 2. Approve order (storekeeper)
    public function approve(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be approved.');
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
            ]);

            // Deduct stock
            foreach ($order->items as $item) {
                StockMovement::create([
                    'product_id' => $item->product_id,
                    'quantity' => -$item->quantity,
                    'type' => 'sale',
                    'reference_id' => $order->id,
                    'reference_type' => Order::class,
                    'created_by' => auth()->id(),
                ]);
            }

            // Create journal entry
            JournalEntry::create([
                'order_id' => $order->id,
                'debit' => $order->total_amount,
                'credit' => $order->total_amount,
                'description' => "Sale of products Invoice #{$order->order_number}",
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with('success', "Order {$order->order_number} approved.");
    }

    public function assignDelivery(Order $order, Request $request)
    {
        $request->validate([
            'delivery_person_id' => 'required|exists:users,id',
        ]);

        $order->update([
            'delivery_person_id' => $request->delivery_person_id,
            'status' => 'assigned',
        ]);

        return back()->with('success', "Order {$order->order_number} assigned for delivery.");
    }

    public function markDelivered(Order $order)
    {
        $order->update(['status' => 'delivered']);
        return back()->with('success', "Order {$order->order_number} marked as delivered.");
    }

    public function markReturned(Order $order, Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.order_item_id' => 'required|exists:order_items,id',
            'items.*.returned_qty' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $ret) {
            $orderItem = OrderItem::find($ret['order_item_id']);
            $orderItem->update([
                'returned_qty' => $orderItem->returned_qty + $ret['returned_qty'],
            ]);

            // TODO: Add stock back
            $orderItem->product->increment('stock', $ret['returned_qty']);
        }

        $order->update(['status' => 'returned']);

        return back()->with('success', "Order {$order->order_number} updated with returns.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $Order)
    {
        $Order->load('customer', 'items.product');
        return view('admin.sales.show', compact('Order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $Order)
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('admin.purchase.edit', compact('Order', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(Request $request, Order $Order)
    {
        $data = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'], // Ensure supplier exists
            'status' => ['nullable', 'in:pending,completed,canceled'], // Customize statuses as needed
            'delivery_date' => ['required', 'date'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'exists:products,id'], // Ensure products exist
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data, $Order) {
            // Step 1: Rollback stock for existing items
            foreach ($Order->items()->get() as $existingItem) {
                $product = Product::find($existingItem->product_id);
                if ($product) {
                    // Subtract the existing item's quantity from the stock
                    $product->stock_quantity -= $existingItem->quantity;
                    $product->save(); // Save the updated stock
                }
            }

            // Step 2: Update the purchase order
            $Order->update([
                'supplier_id' => $data['supplier_id'],
                'status' => $data['status'],
                'delivery_date' => $data['delivery_date'],
            ]);

            // Step 3: Delete existing items and re-add with updated quantities
            $Order->items()->delete(); // Remove existing items

            // Step 4: Add new items and update stock quantities
            foreach ($data['items'] as $itemData) {
                // Add new item to purchase order
                $Order->items()->create($itemData);

                // Update stock with new quantities
                $product = Product::find($itemData['product_id']);
                if ($product) {
                    // Add the new quantity to the stock
                    $product->stock_quantity += $itemData['quantity'];
                    $product->save(); // Save updated stock
                }
            }
        });

        return response()->json([
            'success' => 'Sales order updated successfully.',
            'url' => route('admin.sale-orders.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Order $Order)
    {
        if ($Order->status !== Status::ORDER) {
            return response()->json(['error' => 'Only pending orders can be deleted.']);
        }
        DB::beginTransaction();
        // rollback stock
        $this->rollbackStock($Order);
        $Order->items()->delete(); // Delete all items
        $Order->delete(); // Delete the purchase order
        DB::commit();
        return response()->json(['success' => 'Purchase order deleted successfully.']);
    }

    /**
     * @throws \Throwable
     */
    public function cancel(Order $Order)
    {
        if ($Order->status !== Status::ORDER) {
            return response()->json(['error' => 'Only pending orders can be canceled.']);
        }
        DB::beginTransaction();
        $Order->update(['status' => Status::CANCELLED]);
        // rollback stock
        $this->rollbackStock($Order);
        DB::commit();
        return response()->json(['success' => 'Sales order canceled successfully.']);
    }


    public function print(Order $Order)
    {
        $Order->load('customer', 'items.product');
        $data = QrCode::size(512)
//            ->format('png')
//            ->merge(public_path('assets/media/logos/logo.png'), 0.3, true)
            ->errorCorrection('M')
            ->generate(
                route('home.order-verify', encodeId($Order->id))
            );
        $downloadName = 'sales-order-' . $Order->invoice_number . now()->toDateTimeLocalString() . '.pdf';
        $pdf = Pdf::loadView('admin.sales.print', compact('Order', 'data'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream($downloadName);

    }

    /**
     * @param Order $Order
     * @return void
     */
    public function rollbackStock(Order $Order): void
    {
        $Order->load('items.product');
        foreach ($Order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->stock_quantity += $item->quantity;
                $product->save();
            }
        }
    }

    public function search()
    {
        $orderNumber = \request('order_number');
        $Order = Order::query()
            ->where(DB::raw('LOWER(invoice_number)'), '=', strtolower($orderNumber))
            ->first();

        if (is_null($Order)) {
            return response()->json([
                'success' => false,
                'message' => 'Sale order not found.'
            ], 404);
        }
        $amountToPay = $Order->items()->sum(DB::raw('price * quantity'));
        $amountPaid = $Order->payments()->whereRaw('lower(status)="paid"')->sum('amount');
        $remaining = $amountToPay - $amountPaid;

        return view('admin.sales._search', compact('Order', 'amountPaid', 'remaining', 'amountToPay'));
    }

    /**
     * @param int $amountPaid
     * @param float|int $total_amount
     * @param Order $order
     * @return void
     */
    public function updateCustomerBalance(int $amountPaid, float|int $total_amount, Order $order): void
    {
        if ($amountPaid > $total_amount) {
            $customer = Customer::find($order->customer_id);
            $customer->balance += $amountPaid - $total_amount;
        }
    }
}
