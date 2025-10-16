<?php

namespace App\Http\Controllers;

use App\Constants\TransactionType;
use App\Exports\PurchaseHistoryExport;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index()
    {
        if (\request()->ajax()) {

            $startDate = \request('start_date');
            $endDate = \request('end_date');
            $supplierId = \request('supplier_id');
            $data = Purchase::query()
                ->withSum('items', DB::raw('unit_price * quantity'))
                ->with('supplier')
                ->withCount('items')
                ->when($startDate, fn($query, $startDate) => $query->whereDate('purchased_at', '>=', $startDate))
                ->when($endDate, fn($query, $endDate) => $query->whereDate('purchased_at', '<=', $endDate))
                ->when($supplierId, fn($query, $supplierId) => $query->where('supplier_id', $supplierId));


            return DataTables::of($data)
                ->addColumn('action', function ($purchaseOrder) {
                    return '<div class="dropdown">
                                <button class="btn btn-light btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="' . route('admin.purchases.show', encodeId($purchaseOrder->id)) . '" >Details</a>
                                    <a class="dropdown-item" href="' . route('admin.purchases.print', encodeId($purchaseOrder->id)) . '" >Print</a>
                                    <a class="dropdown-item js-edit" href="' . route('admin.purchases.edit', encodeId($purchaseOrder->id)) . '" >Edit</a>
                                    <a class="dropdown-item js-delete" href="' . route('admin.purchases.destroy', encodeId($purchaseOrder->id)) . '">Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $suppliers = Supplier::all();
        return view('admin.purchase.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchase.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(Request $request)
    {
//        return $request->all();
        $data = $this->validatePurchaseOrder($request);
        try {
            DB::transaction(function () use ($data) {
                $purchaseOrder = $this->createPurchaseOrder($data);
                $totalAmount = $this->processItems($purchaseOrder, $data);
                $purchaseOrder->update([
                    'total_amount' => $totalAmount,
                ]);
                $this->createJournal($purchaseOrder, $totalAmount);
            });

            return $request->ajax()
                ? response()->json(['success' => 'Purchase order saved successfully.', 'url' => route('admin.purchases.index')])
                : redirect()->route('admin.purchases.index')->with('success', 'Purchase order saved successfully.');

        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['error' => $e->getMessage()], 422)
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

// ---------- Private helper methods ----------

    /**
     * @throws Exception
     */
    private function validatePurchaseOrder(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'product_ids' => ['required', 'array'],
            'quantities' => ['required', 'array'],
            'prices' => ['required', 'array'],
            'exp_dates' => ['sometimes', 'array'],
            'exp_dates.*' => ['nullable', 'date', 'after:today']
        ]);

        if (count($data['product_ids']) !== count($data['quantities']) ||
            count($data['product_ids']) !== count($data['prices'])) {
            throw new \Exception('Product, quantity, and price counts must match.');
        }

        return $data;
    }

    private function createPurchaseOrder(array $data)
    {
        $purchaseOrder = Purchase::create([
            'supplier_id' => $data['supplier_id'],
            'purchased_at' => $data['purchased_at'],
            'user_id' => auth()->id(),
            'total_amount' => 0
        ]);

        $purchaseOrder->generateInvoiceNumber();
        return $purchaseOrder;
    }

    /**
     * @throws Exception
     */
    private function processItems(Purchase $purchaseOrder, array $data)
    {
        $totalAmount = 0;

        foreach ($data['product_ids'] as $index => $product_id) {
            $qty = $data['quantities'][$index];
            $price = $data['prices'][$index];
            $exp_date = $data['exp_dates'][$index];
            if ($qty <= 0) {
                throw new \Exception("Invalid quantity for product ID: {$product_id}");
            }

            $purchaseOrder->items()->create([
                'product_id' => $product_id,
                'quantity' => $qty,
                'unit_price' => $price,
                'expiration_date' => $exp_date
            ]);

            $product = Product::findOrFail($product_id);
            $product->stock += $qty;
            $product->save();

            StockMovement::create([
                'product_id' => $product_id,
                'quantity' => $qty,
                'type' => 'in',
                'reference_type' => $purchaseOrder->getMorphClass(),
                'reference_id' => $purchaseOrder->id,
            ]);

            $totalAmount += $qty * $price;
        }

        return $totalAmount;
    }

    private function createJournal(Purchase $purchaseOrder, float $totalAmount)
    {
        $journalEntry = JournalEntry::create([
            'reference_type' => $purchaseOrder->getMorphClass(),
            'reference_id' => $purchaseOrder->id,
            'description' => 'Purchase order #' . $purchaseOrder->invoice_number,
            'date' => now(),
            'created_by' => auth()->id(),
        ]);

        JournalLine::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => Account::INVENTORY_ID,
            'type' => TransactionType::DEBIT,
            'amount' => $totalAmount,
        ]);

        JournalLine::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => Account::ACCOUNTS_PAYABLE_ID,
            'type' => TransactionType::CREDIT,
            'amount' => $totalAmount,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchaseOrder = $purchase;
        $purchaseOrder->load('supplier', 'items.product');
        return view('admin.purchases.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchaseOrder)
    {
        $purchaseOrder->items()->delete(); // Delete all items
        $purchaseOrder->delete(); // Delete the purchase order
        return response()->json(['success' => 'Purchase order deleted successfully.']);
    }


    public function print(Purchase $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'items.product');

        $downloadName = 'purchase-order-' . $purchaseOrder->invoice_number . now()->toDateTimeLocalString() . '.pdf';
        $pdf = Pdf::loadView('admin.purchases.print', compact('purchaseOrder'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream($downloadName);
    }

    public function history()
    {
        $products = Product::query()->whereHas('purchases')->latest()->get();
        return view('admin.purchase.history', compact('products'));
    }

    public function exportHistory(ReportService $reportService)
    {
        $startDate = \request('start_date');
        $endDate = \request('end_date');
        $productId = \request('product_id');

        $data = $reportService->getPurchaseQueryBuilder($startDate, $endDate, $productId)
            ->get();
        $pdf = PDF::loadView('admin.purchase.pdf-history', compact('data', 'startDate', 'endDate'));
        return $pdf->stream("purchase-history.pdf");
    }
}
