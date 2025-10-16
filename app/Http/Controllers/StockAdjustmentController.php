<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Services\FlowHistoryService;
use DB;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class StockAdjustmentController extends Controller
{
    private FlowHistoryService $flowHistoryService;

    public function __construct()
    {
        $this->flowHistoryService = new FlowHistoryService();
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        if (\request()->ajax()) {
            $source = StockAdjustment::with(['requester', 'approver'])
                ->withCount('items');
            return datatables($source)
                ->addIndexColumn()
                ->addColumn('action', fn(StockAdjustment $adjustment) => view('admin.stock_adjustments.partials._actions', compact('adjustment')))
                ->rawColumns(['action'])
                ->make(true);

        }
        return view('admin.stock_adjustments.index');
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

//        return $request->all();

        $data = $request->validate([
            'reason' => 'required|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.type' => 'required|in:increase,decrease',
            'items.*.quantity' => 'required|integer|min:1', // Quantities should always be positive
        ]);

        DB::transaction(function () use ($data) {
            $adjustment = StockAdjustment::query()->create([
                'reason' => $data['reason'],
                'requested_by' => auth()->id(),
                'status' => Status::Pending,
            ]);

            foreach ($data['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                $adjustment->items()->create([
                    'product_id' => $product->id,
                    'type' => $itemData['type'],
                    'quantity' => $itemData['quantity'],
                    'quantity_before' => $product->stock, // Record stock at time of request
                ]);
            }


            $this->flowHistoryService->saveFlow($adjustment->getMorphClass(),
                $adjustment->id, "Stock Adjustment Requested",
                Status::Pending, false, auth()->id()
            );
        });

        return redirect()->route('admin.stock-adjustments.index')->with('success', 'Adjustment request submitted for approval.');
    }

    public function show(StockAdjustment $stockAdjustment)
    {
        $stockAdjustment->load(['items.product', 'requester', 'approver']);
        return view('admin.stock_adjustments.show', compact('stockAdjustment'));
    }

    /**
     * @throws Throwable
     */
    public function review(Request $request, StockAdjustment $stockAdjustment)
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'comment' => ['nullable', 'required_if:status,rejected', 'string', 'max:500']
        ]);
        if (strtolower($stockAdjustment->status) !== strtolower(Status::Pending)) {
            return back()->with('error', 'This request has already been processed.');
        }

        DB::transaction(function () use ($data, $stockAdjustment) {
            if (strtolower($data['status']) == strtolower(Status::Approved)) {
                foreach ($stockAdjustment->items as $item) {
                    if ($item->type === 'increase') {
                        $item->product->increment('stock', $item->quantity);
                    } else { // 'decrease'
                        $item->product->decrement('stock', $item->quantity);
                    }
                }
            }


            $stockAdjustment->update([
                'status' => $data['status'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
            $this->flowHistoryService->saveFlow($stockAdjustment->getMorphClass(),
                $stockAdjustment->id, $data['comment'],
                $data['status'], true, auth()->id()
            );

        });

        if ($request->ajax()){
            session()->flash('success', 'Stock adjustment has been reviewed successfully.');
            return $stockAdjustment;
        }
        return redirect()->route('admin.stock-adjustments.show', $stockAdjustment)
            ->with('success', 'Stock adjustment has been reviewed successfully.');

    }

}
