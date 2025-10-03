<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index()
    {
        if (request()->ajax()) {
            $products = Product::query()
                ->with(['category']);
            return DataTables::of($products)
                ->addColumn('action', function ($product) {
                    // dropdown
                    return '<div class="dropdown">
                                <button class="btn btn-light btn-icon btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item js-edit" href="' . route('admin.products.show', $product->id) . '" >Edit</a>
                                    <a class="dropdown-item js-delete" href="' . route('admin.products.destroy', $product->id) . '">Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $categories = Category::all();
        return view('admin.products.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string', 'max:255'],
            'unit_measure' => ['required', 'string', 'max:255'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'image_ids' => 'nullable|array',

        ]);
        if (empty($data['sku'])) {
            $data['sku'] = Product::generateSku($data['name'], $data['category_id']);
        }
        unset($data['image_ids']);
        if (empty($data['id'])) {
            // Create new product
            $data['stock'] = 0;
            $product = Product::create($data);
        } else {
            // Update existing product
            $product = Product::findOrFail($data['id']);
            $product->update($data);
        }
        if ($request->filled('image_ids')) {
            foreach ($request->image_ids as $id) {
                $image = Image::find($id);
                if ($image) {
                    // Move file from tmp to permanent folder
                    $newPath = 'products/' . basename($image->path);
                    Storage::move($image->path, $newPath);

                    // Update database record
                    $image->update([
                        'path' => $newPath,
                        'imageable_id' => $product->id,
                    ]);
                }
            }
        }

        return response()->json(['success' => 'Product saved successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => 'Product deleted successfully.']);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function uploadTempImages(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB
        ]);

        // Store temporarily
        $path = $request->file('file')->store('tmp/products');

        // Save to database as pending (no product yet)
        $image = Image::query()->create([
            'path' => $path,
            'imageable_type' => Product::class, // will associate later
            'imageable_id' => null, // not linked yet
            'is_primary' => false,
        ]);

        return response()->json(['image_id' => $image->id]);
    }

    public function catalog()
    {
        $products = Product::query()->with(['images','category'])
            ->latest()
            ->paginate(12)->withQueryString();
        return view('admin.products.catalog', compact('products'));
    }
}
