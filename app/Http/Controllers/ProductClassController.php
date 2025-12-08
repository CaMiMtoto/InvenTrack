<?php

namespace App\Http\Controllers;

use App\Models\ProductClass;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductClassController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $productClasses = ProductClass::query()
                ->withCount('products');
            return DataTables::of($productClasses)
                ->addColumn('action', function ($productClass) {
                    // dropdown
                    return '<div class="dropdown">
                                <button class="btn btn-secondary btn-sm btn-icon dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item js-edit" href="' . route('admin.products.product-class.show', $productClass->id) . '" >Edit</a>
                                    <a class="dropdown-item js-delete" href="' . route('admin.products.product-class.destroy', $productClass->id) . '">Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.products.product_classes');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required'],
            'name' => ['required', 'string', 'max:255', 'unique:product_classes,name,' . $request->id],
            'rate' => ['required', 'numeric', 'min:0'],
        ]);

        if ($data['id'] == 0) {
            ProductClass::create($data);
        } else {
            $productClass = ProductClass::find($data['id']);
            $productClass->update($data);
        }

        return response()->json(['success' => 'Product class saved successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductClass $productClass)
    {
        return response()->json($productClass);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductClass $productClass)
    {
        $productClass->delete();
        return response()->json(['success' => 'Product class deleted successfully.']);
    }
}
