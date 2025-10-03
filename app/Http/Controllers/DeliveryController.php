<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\FlowHistory;
use App\Models\Order;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class DeliveryController extends Controller
{
    // List all deliveries
    public function index()
    {
        $deliveries = Delivery::with(['order', 'deliveryPerson'])->get();
        return view('admin.deliveries.index', compact('deliveries'));
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
                        'status' => 'pending'
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
    public function updateOrderStatus(Request $request, Delivery $delivery)
    {

        $data = $request->validate([
            'status' => 'required|in:pending,transit,delivered,partially_delivered',
            'comment' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($delivery, $data) {
            $delivery->status = $data['status'];
            $delivery->notes = $data['comment'] ?? null;
            $delivery->delivered_at = now();
            $delivery->save();

            // Update item-level status only if fully delivered
            if ($data['status'] === 'delivered') {
                foreach ($delivery->items as $item) {
                    if ($item->status !== 'returned') {
                        $item->status = 'delivered';
                        $item->save();

                        // Adjust stock
                        /*  $product = $item->orderItem->product;
                          $product->stock -= $item->quantity;
                          $product->save();*/

                        // Record flow history
                        FlowHistory::create([
                            'reference_type' => DeliveryItem::class,
                            'reference_id' => $item->id,
                            'action' => 'delivered',
                            'comment' => 'Delivered via order update',
                            'user_id' => auth()->id()
                        ]);
                    }
                }
            }

            // Optionally, if status is partially_delivered, we leave items unchanged
            // They can be updated individually if some items are returned later

            // Record flow history for the order
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
                ->withCount('items')
                ->where('delivery_person_id','=',auth()->id());
            return datatables($source)
                ->addIndexColumn()
                ->addColumn('action', fn(Delivery $delivery) => view('admin.deliveries._actions', compact('delivery')))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.deliveries.my_deliveries');
    }
}
