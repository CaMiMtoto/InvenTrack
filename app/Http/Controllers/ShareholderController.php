<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Models\LegalType;
use App\Models\Shareholder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Validator;
use Yajra\DataTables\DataTables;

class ShareholderController extends Controller
{


    /**
     * Display a listing of the resource.
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Shareholder::with('legalType')
                ->withSum('shares','value');

            return DataTables::of($query)
                ->addColumn('action', fn($shareholder) => view('admin.shareholders._actions', compact('shareholder'))->render())
                ->editColumn('created_at', function ($shareholder) {
                    return $shareholder->created_at->format('d M Y, h:i a');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $legalTypes = LegalType::all();
        return view('admin.shareholders.index', compact('legalTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateShareholder($request);
        $validated['user_id']=auth()->id();
        Shareholder::create($validated);

        return response()->json(['success' => 'Shareholder created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shareholder $shareholder)
    {
        return response()->json($shareholder);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shareholder $shareholder)
    {
        $validated = $this->validateShareholder($request, $shareholder->id);

        $shareholder->update($validated);

        return response()->json(['success' => 'Shareholder updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shareholder $shareholder)
    {
        $shareholder->delete();

        return response()->json(['success' => 'Shareholder deleted successfully.']);
    }

    /**
     * Validate the request for storing or updating a shareholder.
     */

    private function validateShareholder(Request $request, $shareholderId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'legal_type_id' => 'required|exists:legal_types,id',
            'id_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shareholders', 'id_number')->ignore($shareholderId),
            ],
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('shareholders', 'phone_number')->ignore($shareholderId),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('shareholders', 'email')->ignore($shareholderId),
            ],
            'tin' => ['nullable', 'numeric'], // 'number' → 'numeric'
            'nationality' => ['nullable', 'string', 'max:255'],
            'residential_address' => ['nullable', 'string', 'max:255'],
            'birth_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($value && \Carbon\Carbon::parse($value)->age < 18) {
                        $fail('The shareholder must be at least 18 years old.');
                    }
                }
            ],
        ];

        return $request->validate($rules);
    }

    public function shares(Shareholder $shareholder)
    {
        $shareholder->load('shares');
        return view('admin.shareholders.shares', compact('shareholder'));
    }

    public function storeShare(Request $request, Shareholder $shareholder)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
            'value' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shareholder->shares()->create([
            'quantity' => $request->input('quantity'),
            'value' => $request->input('value'),
            'status' => 'active', // Setting a default status
        ]);

        return response()->json(['message' => 'Share added successfully.']);
    }

}
