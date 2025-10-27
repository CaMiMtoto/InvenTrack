<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;

class OrderItemController extends Controller
{
    public function destroy(OrderItem $orderItem)
    {
        try {
            $orderItem->delete();
            return response()->json(['success' => 'Order item deleted successfully.']);
        } catch (\Exception $e) {
            info('Error deleting order item: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete order item.'], 500);
        }
    }
}
