<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->paginate(10);
        return view('admin.purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
//            'invoice_number' => 'required|unique:purchases',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_date' => 'required|date',
//            'status'         => 'required|in:pending,completed,cancelled',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
        \DB::beginTransaction();
        $purchase = Purchase::create([
            'supplier_id' => $data['supplier_id'],
            'purchased_at' => $data['purchase_date'],
            'total_amount' => 0,
            'user_id'=>auth()->id()
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $subtotal = $item['quantity'] * $item['unit_price'];
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);

            if ($purchase->status === 'completed') {
                $product = Product::find($item['product_id']);
                $product->increaseStock($item['quantity']);
            }

            $total += $subtotal;
        }

        $purchase->update(['total_amount' => $total]);
        \DB::commit();
        return redirect()->route('admin.purchases.index')->with('success', 'Purchase recorded successfully!');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('items.product', 'supplier');
        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
