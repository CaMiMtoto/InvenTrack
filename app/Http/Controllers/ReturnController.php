<?php

namespace App\Http\Controllers;

use App\Constants\TransactionType;
use App\Models\Account;
use App\Models\DamagedItem;
use App\Models\Delivery;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\ReturnModel;
use App\Models\StockMovement;
use DB;
use Illuminate\Http\Request;
use Throwable;

class ReturnController extends Controller
{
    public function store(Request $request,Delivery $delivery)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition' => 'required|in:good,damaged',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $returnRequest = ReturnModel::create([
            'order_id' => $validated['order_id'],
            'delivery_person_id' => auth()->id(),
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending_approval',
        ]);

        foreach ($validated['items'] as $item) {
            ReturnItem::create([
                'return_request_id' => $returnRequest->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'condition' => $item['condition'],
            ]);
        }

        return response()->json(['message' => 'Return request submitted successfully.']);
    }

    /**
     * @throws Throwable
     */
    public function approve(ReturnModel $returnRequest)
    {
        DB::transaction(function () use ($returnRequest) {
            $returnRequest->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            foreach ($returnRequest->items as $item) {
                if ($item->condition === 'good') {
                    $item->product->increment('stock', $item->quantity);
                } else {
                    DamagedItem::create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'reason' => $returnRequest->reason,
                        'reported_by' => $returnRequest->delivery_person_id,
                        'approved_by' => auth()->id(),
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Return request approved successfully.']);
    }

}
