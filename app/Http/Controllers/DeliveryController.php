<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Constants\Status;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\FlowHistory;
use App\Models\Order;
use App\Models\ReturnModel;
use App\Models\ReturnReason;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class DeliveryController extends Controller
{
    // List all deliveries
    /**
     * @throws Exception
     */
    public function index()
    {
        if (\request()->ajax()){
            $source = Delivery::with(['order', 'deliveryPerson']);
            return datatables($source)
                ->addIndexColumn()
                ->addColumn('action', fn(Delivery $delivery) => view('admin.deliveries._actions', compact('delivery')))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.deliveries.index');
    }

    // Show form to assign deliveries


    // Store bulk assignment
    /**
     * @throws Throwable
     */
    public function bulkAssign(Request $request)
    {
        $data = $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'delivery_person_id' => 'required|exists:users,id',
            'comment' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($data) {
            $deliveryPerson = User::findOrFail($data['delivery_person_id']);

            foreach ($data['order_ids'] as $orderId) {
                $order = Order::findOrFail($orderId);

                $delivery = Delivery::create([
                    'order_id' => $order->id,
                    'delivery_person_id' => $deliveryPerson->id,
                    'status' => 'pending',
                    'notes' => $data['comment'] ?? null
                ]);

                // Create delivery items for each order item
                foreach ($order->items as $orderItem) {
                    DeliveryItem::create([
                        'delivery_id' => $delivery->id,
                        'order_item_id' => $orderItem->id,
                        'quantity' => $orderItem->quantity,
                        'delivered_quantity' => 0
                    ]);
                }

                // Record flow history for each delivery
                FlowHistory::create([
                    'reference_type' => Delivery::class,
                    'reference_id' => $delivery->id,
                    'action' => 'assigned',
                    'comment' => 'Assigned to ' . $deliveryPerson->name,
                    'user_id' => auth()->id()
                ]);
            }
        });

        return redirect()->back()->with('success', 'Selected orders assigned successfully.');
    }

    // Update delivery status

    // Update order-level delivery status
    /**
     * @throws Throwable
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {

        $data = $request->validate([
            'status' => 'required|in:pending,transit,delivered,partially delivered',
            'comment' => 'required|string|max:255'
        ]);

        DB::transaction(function () use ($delivery, $data) {
            $delivery->status = $data['status'];
            $delivery->notes = $data['comment'] ?? null;
            $delivery->save();
            if (strtolower($data['status']) === strtolower(Status::Delivered)) {
                $delivery->delivered_at = now();
                $delivery->order()->update([
                    'order_status' => Status::Delivered
                ]);
                $delivery->save();
                foreach ($delivery->items as $item) {
                    $item->delivered_quantity = $item->quantity;
                    $item->save();
                }
            }
            FlowHistory::create([
                'reference_type' => Delivery::class,
                'reference_id' => $delivery->id,
                'action' => $data['status'],
                'comment' => $data['comment'] ?? null,
                'user_id' => auth()->id()
            ]);
        });

        return redirect()->back()->with('success', 'Delivery updated successfully.');
    }

    /**
     * @throws Exception
     */
    public function myDeliveries()
    {
        if (\request()->ajax()) {
            $source = Delivery::query()
                ->with(['order.customer'])
                ->withCount(['items', 'returns'])
                ->where('delivery_person_id', '=', auth()->id());
            return datatables($source)
                ->addIndexColumn()
                ->addColumn('action', fn(Delivery $delivery) => view('admin.deliveries._actions', compact('delivery')))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.deliveries.my_deliveries');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load(['order.customer', 'items.orderItem.product']);
        return view('admin.deliveries._show', compact('delivery'));
    }

    public function returns(Delivery $delivery)
    {
        $delivery->load(['order.customer', 'items.orderItem.product']);
        $reasons = ReturnReason::query()->latest()->get();
        return view('admin.deliveries._returns', compact('delivery', 'reasons'));
    }

    /**
     * @throws Throwable
     */
    public function processReturn(Delivery $delivery, Request $request)
    {
        $data = $request->validate([
            'comment' => ['nullable', 'string', 'max:500'],
            'returns' => ['required', 'array'],
            'returns.*' => ['integer', 'min:0'],
            'return_reasons' => ['required', 'array'],
            'return_reasons.*' => ['nullable', 'exists:return_reasons,id'],
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

// Manual post-validation check
        foreach ($data['returns'] as $index => $quantity) {
            if ($quantity > 0 && empty($data['return_reasons'][$index])) {
                throw ValidationException::withMessages([
                    "return_reasons.$index" => "Return reason is required when return quantity is greater than 0.",
                ]);
            }
        }
        $totalReturns = array_sum($data['returns']);
        $totalItems = $delivery->items->sum('quantity');

        if ($totalReturns == 0) {
            return response()->json(['message' => 'No items were returned.'], 422);
        }

        DB::transaction(function () use ($delivery, $data, $totalReturns, $totalItems) {
            // 1. Create the main return record
            $return = $delivery->returns()->create([
                'order_id' => $delivery->order_id,
                'done_by' => auth()->id(),
                'status' => Status::Submitted,
                'reason' => $data['comment'], // General comment for the return
            ]);

            // 2. Process each returned item
            foreach ($data['returns'] as $index => $returnQty) {
                if ($returnQty <= 0) {
                    continue; // Skip items that were not returned
                }

                $productId = $data['product_ids'][$index];
                $reasonId = $data['return_reasons'][$index];

                // Find the corresponding delivery item
                $deliveryItem = $delivery->items()->whereHas('orderItem', function ($query) use ($productId) {
                    $query->where('product_id', $productId);
                })->firstOrFail();

                // Create a record for the specific returned item
                $return->items()->create([
                    'product_id' => $productId,
                    'quantity' => $returnQty,
                    'return_reason_id' => $reasonId,
                ]);

                // Update the delivered quantity for this item
                $newDeliveredQty = $deliveryItem->quantity - $returnQty;
                $deliveryItem->update(['delivered_quantity' => max(0, $newDeliveredQty)]);

            }

            // 3. Update the overall delivery status
            $newStatus = ($totalReturns < $totalItems) ? Status::PartiallyDelivered : Status::Returned;
            $delivery->update([
                'status' => $newStatus,
                'delivered_at' => now(), // Mark as processed now
            ]);

            // 4. Log the action
            FlowHistory::create([
                'reference_type' => $delivery->getMorphClass(),
                'reference_id' => $delivery->id,
                'action' => 'Returned',
                'comment' => 'Return processed with ' . $totalReturns . ' item(s).',
                'user_id' => auth()->id(),
            ]);
        });

        return response()->json(['message' => 'Return processed successfully.']);
    }
}
