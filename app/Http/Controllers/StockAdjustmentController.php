<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Product;
use App\Models\StockAdjustment;
use DB;
use Illuminate\Http\Request;
use Throwable;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        // TODO: Create index view with a DataTable to list adjustments
        // You can use the logic from DeliveryController@myDeliveries as a template
        $adjustments = StockAdjustment::with(['requester', 'approver'])->latest()->get();
        return view('admin.stock_adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        // TODO: Create a view with a form to add products and quantities
        $products = Product::query()->orderBy('name')->get();
        return view('admin.stock_adjustments.create', compact('products'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'reason' => 'required|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|not_in:0',
        ]);

        DB::transaction(function () use ($data) {
            $adjustment = StockAdjustment::create([
                'reason' => $data['reason'],
                'requested_by' => auth()->id(),
                'status' => Status::Pending,
            ]);

            foreach ($data['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                $adjustment->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'quantity_before' => $product->stock, // Record stock at time of request
                ]);
            }
        });

        return redirect()->route('admin.stock-adjustments.index')->with('success', 'Adjustment request submitted for approval.');
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        // TODO: Create a detail view
        $stockAdjustment->load(['items.product', 'requester', 'approver']);
        return view('admin.stock_adjustments.show', compact('stockAdjustment'));
    }

    /**
     * @throws Throwable
     */
    public function approve(StockAdjustment $stockAdjustment)
    {
        if ($stockAdjustment->status !== Status::Pending) {
            return back()->with('error', 'This request has already been processed.');
        }

        DB::transaction(function () use ($stockAdjustment) {
            foreach ($stockAdjustment->items as $item) {
                // Use increment/decrement for safe concurrent updates
                if ($item->quantity > 0) {
                    $item->product->increment('stock', $item->quantity);
                } else {
                    $item->product->decrement('stock', abs($item->quantity));
                }
            }

            $stockAdjustment->update([
                'status' => Status::Approved,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        });

        return redirect()->route('admin.stock-adjustments.show', $stockAdjustment)->with('success', 'Stock adjustment approved and applied.');
    }

    public function reject(Request $request, StockAdjustment $stockAdjustment)
    {
        if ($stockAdjustment->status !== Status::Pending) {
            return back()->with('error', 'This request has already been processed.');
        }

        $data = $request->validate(['rejection_reason' => 'required|string|max:1000']);

        // We can append the rejection reason to the main reason field
        $stockAdjustment->update([
            'status' => Status::Rejected,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'reason' => $stockAdjustment->reason . "\n\nRejected: " . $data['rejection_reason'],
        ]);

        return redirect()->route('admin.stock-adjustments.show', $stockAdjustment)->with('success', 'Stock adjustment has been rejected.');
    }
}