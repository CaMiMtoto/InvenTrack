<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\District;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Storage;
use Yajra\DataTables\Exceptions\Exception;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     * @throws \Exception
     */
    public function index()
    {

        if (request()->ajax()) {
            $data = Customer::query();
            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('created_at', fn($row) => date('d-m-Y,h:i', strtotime($row->created_at)))
                ->addColumn('action', fn(Customer $row) => view('admin.settings.customers._actions', compact('row')))
                ->rawColumns(['action'])
                ->make(true);
        }
        $districts = District::query()->get();
        return view('admin.settings.customers', compact('districts'));
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'min:0'],
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'tin' => ['sometimes', 'regex:/^[0-9]{9}$/'],
            'landmark' => ['required', 'max:255'],
            'nickname' => ['nullable', 'max:255'],
            'id_number' => ['sometimes', 'regex:/^[0-9]{16}$/'],
            'district_id' => ['required', 'exists:districts,id'],
            'sector_id' => ['required', 'exists:sectors,id'],
            'cell_id' => ['required', 'exists:cells,id'],
            'village_id' => ['nullable', 'exists:villages,id'],
            'address_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'longitude' => ['nullable', 'regex:/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/'],
            'latitude' => ['nullable', 'regex:/^[-+]?(90(\.0+)?|([1-8]?\d(\.\d+)?))$/'],
        ]);

        // check if a customer already exists by name and phone number
        $existingCustomer = Customer::where('name', $data['name'])
            ->where('phone', $data['phone'])
            ->first();
        if ($existingCustomer && $existingCustomer->id != $data['id']) {
            return response()->json(['message' => 'A customer with the same name and phone number already exists.'], 400);
        }

        // Handle file upload
        if ($request->hasFile('address_photo')) {
            // If updating, delete an old photo
            if ($data['id'] != 0) {
                $existing = Customer::find($data['id']);
                if ($existing && $existing->address_photo) {
                    Storage::delete($existing->address_photo);
                }
            }
            // Store new photo
            $data['address_photo'] = $request->file('address_photo')->store('address_photos');
        }

        // Create or update a customer
        if ($data['id'] == 0) {
            Customer::create($data);
        } else {
            Customer::where('id', $data['id'])->update($data);
        }

        return response()->json(['success' => 'Customer saved successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => 'Customer deleted successfully.']);
    }

    public function details(Customer $customer)
    {
        $customer->load(['district', 'sector', 'cell', 'village']);
        return view('admin.settings.customers._details', compact('customer'));
    }
}
