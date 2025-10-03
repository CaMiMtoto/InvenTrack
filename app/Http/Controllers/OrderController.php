<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Constants\Status;
use App\Constants\TransactionType;
use App\Models\Account;
use App\Models\ApprovalFlow;
use App\Models\Customer;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Support\QRCode;
use Yajra\DataTables\Exceptions\Exception;

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
                ->withSum('items', DB::raw("quantity * unit_price"))
                ->when($startDate, fn($query, $startDate) => $query->whereDate('order_date', '>=', $startDate))
                ->when($endDate, fn($query, $endDate) => $query->whereDate('order_date', '<=', $endDate))
                ->when($status, fn($query, $status) => $query->where('order_status', $status));

            return \DataTables::of($data)
                ->addColumn('action', fn(Order $saleOrder) => view('admin.sales.partials.actions', compact('saleOrder')))
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
                'order_status' => $data['status'] ?? 'pending',
                'order_date' => $data['order_date'],
                'total_amount' => $total_amount,
                'created_by' => auth()->id()
            ]);

            $order->generateOrderNumber();

            // Loop through items and add them to the purchase order

            foreach ($data['product_ids'] as $index => $product_id) {
                $qty = $data['quantities'][$index];
                $price = $data['prices'][$index];
                $order->items()->create([
                    'product_id' => $product_id,
                    'quantity' => $qty,
                    'unit_price' => $price,
                ]);

                $total_amount += $qty * $price;
                // Find and update product stock quantity
                $product = Product::find($product_id);
                $newQty = $qty;

                if ($product) {
                    // Ensure the stock is enough (checking in boxes or units)
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
                'url' => route('admin.orders.index')
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Sales order saved successfully.');
    }

    // 2. Approve order (storekeeper)

    /**
     * @throws \Throwable
     */
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'action' => ['required', 'in:approved,rejected'],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        if (strtolower($order->status) !== 'pending') {
            return response()->json(['error' => 'Only pending orders can be processed.'], 422);
        }

        DB::transaction(function () use ($order, $data) {
            $order->order_status = $data['action'];
            $order->approved_by = auth()->id();
            $order->approved_at = now();
            $order->save();

            // record flow history
            ApprovalFlow::create([
                'approvable_type' => $order->getMorphClass(),
                'approvable_id' => $order->id,
                'user_id' => auth()->id(),
                'status' => $data['action'],
                'comment' => $data['comment'],
            ]);

            if ($data['action'] === 'approved') {
                $this->processApprovedOrder($order);
            }
        });

        return response()->json(['success' => "Order {$data['action']} successfully."]);
    }

    /**
     * @throws \Exception
     */
    protected function processApprovedOrder(Order $order)
    {
        $totalAmount = 0;
        foreach ($order->items as $item) {
            $product = $item->product;

            if ($product->stock < $item->quantity) {
                throw new \Exception("Not enough stock for product: {$product->name}");
            }

            $product->stock -= $item->quantity;
            $product->save();

            StockMovement::create([
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'type' => 'out',
                'reference_type' => $order->getMorphClass(),
                'reference_id' => $order->id,
            ]);

            $totalAmount += $item->quantity * $item->price;
        }

        // Create journal entry
        $journalEntry = JournalEntry::create([
            'reference_type' => $order->getMorphClass(),
            'reference_id' => $order->id,
            'description' => 'Approved Order #' . $order->order_number,
            'date' => now(),
            'created_by' => auth()->id(),
        ]);

        JournalLine::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => Account::ACCOUNTS_RECEIVABLE_ID,
            'type' => TransactionType::DEBIT,
            'amount' => $totalAmount,
        ]);

        JournalLine::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => Account::SALES_REVENUE_ID,
            'type' => TransactionType::CREDIT,
            'amount' => $totalAmount,
        ]);
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
    public function show(Order $saleOrder)
    {
        $saleOrder->load('customer', 'items.product');
        return view('admin.sales.show', compact('saleOrder'));
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
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(Order $Order)
    {
        if ($Order->status !== Status::Pending) {
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
        if ($Order->status !== Status::Pending) {
            return response()->json(['error' => 'Only pending orders can be canceled.']);
        }
        DB::beginTransaction();
        $Order->update(['status' => Status::Cancelled]);
        // rollback stock
        $this->rollbackStock($Order);
        DB::commit();
        return response()->json(['success' => 'Sales order canceled successfully.']);
    }


    public function print(Order $order)
    {
        $order->load('customer', 'items.product');
        $saleOrder = $order;
        $data = QrCode::size(512)
//            ->format('png')
//            ->merge(public_path('assets/media/logos/logo.png'), 0.3, true)
            ->errorCorrection('M')
            ->generate(
                route('home.order-verify', encodeId($order->id))
            );
        $downloadName = 'sales-order-' . $order->invoice_number . now()->toDateTimeLocalString() . '.pdf';
        $pdf = Pdf::loadView('admin.sales.print', compact('saleOrder', 'data'));
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

        return view('admin.orders._search', compact('Order', 'amountPaid', 'remaining', 'amountToPay'));
    }

    /**
     * @throws Exception
     */
    public function pendingDeliveries()
    {
        if (\request()->ajax()) {
            $orders = Order::query()
                ->with(['customer'])
                ->withCount('items')
                ->withSum('items', DB::raw("quantity * unit_price"))
                ->where('order_status', 'approved')
                ->whereDoesntHave('deliveries'); // only approved & not yet assigned
            return datatables($orders)
                ->addColumn('action', fn(Order $order) => view('admin.orders.actions._pending_deliveries', compact('order')))
                ->rawColumns(['action'])
                ->make(true);
        }

        $deliveryPersons = User::permission(Permission::DELIVER_PRODUCTS)->get();
        return view('admin.orders.pending_deliveries', compact('deliveryPersons'));
    }

}
