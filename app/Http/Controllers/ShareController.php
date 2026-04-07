<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Share;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class ShareController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index()
    {
        $status = \request('status');
        $mine= \request('mine');
        if (\request()->ajax()) {
            return datatables(
                Share::query()
                    ->with(['shareholder'])
                    ->when($mine, fn(Builder $query) => $query->where('user_id','=', auth()->id()))
                    ->when($status, fn(Builder $query) => $query->where(DB::raw('LOWER(status)'), '=', strtolower($status)))
            )->addIndexColumn()
                ->editColumn('created_at', fn($row) => date('d-m-Y,h:i', strtotime($row->created_at)))
                ->editColumn('status', fn($row) => ucfirst($row->status))
                ->addColumn('action', fn(Share $share) => view('admin.shares._actions', compact('share')))
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.shares.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Share $share)
    {
        $share->load(['shareholder', 'payment', 'flowHistories']);
        return view('admin.shares.show', compact('share'));
    }

    /**
     * Return share data for editing (AJAX)
     */
    public function edit(Share $share)
    {
        // load relations useful for editing
        $share->load(['payment.paymentMethod', 'payment.bank', 'shareholder']);
        return response()->json($share);
    }

    /**
     * Update the specified share. Only allowed when status is pending.
     */
    public function update(Request $request, Share $share)
    {
        // Only allow editing if pending
        if (strtolower($share->status) !== 'pending') {
            return response()->json(['message' => 'Only pending shares can be edited.'], 403);
        }

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'payment_method_id' => ['nullable', 'exists:payment_methods,id'],
            'bank_id' => ['nullable', 'exists:banks,id'],
            'reference_number' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ]);

        DB::beginTransaction();
        try {
            $share->quantity = $data['quantity'];
            $share->save();

            // update or create payment
            $amount = $share->quantity * $share->value;
            if ($share->payment) {
                $payment = $share->payment;
                $payment->payment_method_id = $data['payment_method_id'] ?? $payment->payment_method_id;
                $payment->bank_id = $data['bank_id'] ?? $payment->bank_id;
                $payment->reference_number = $data['reference_number'] ?? $payment->reference_number;
                $payment->amount = $amount;

                if ($request->hasFile('attachment')) {
                    // store new file and update path
                    $path = $request->file('attachment')->store('payments');
                    $payment->attachment = $path;
                }

                $payment->save();
            } else {
                // create a new payment record if any payment information provided
                if ($data['payment_method_id'] || $data['bank_id'] || $data['reference_number'] || $request->hasFile('attachment')) {
                    $payload = [
                        'payment_method_id' => $data['payment_method_id'] ?? null,
                        'bank_id' => $data['bank_id'] ?? null,
                        'reference_number' => $data['reference_number'] ?? null,
                        'amount' => $amount,
                        'status' => $share->status === 'approved' ? 'paid' : 'pending',
                        'user_id' => auth()->id(),
                    ];
                    if ($request->hasFile('attachment')) {
                        $payload['attachment'] = $request->file('attachment')->store('payments');
                    }
                    $share->payments()->create($payload);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Share updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified share. Only allowed when status is pending.
     */
    public function destroy(Share $share)
    {
        if (strtolower($share->status) !== 'pending') {
            return response()->json(['message' => 'Only pending shares can be deleted.'], 403);
        }

        DB::beginTransaction();
        try {
            // delete related payments and flow histories
            if ($share->payments && $share->payments()->exists()) {
                foreach ($share->payments as $payment) {
                    // if attachments are stored, consider deleting files here
                    $payment->delete();
                }
            }
            if ($share->flowHistories && $share->flowHistories()->exists()) {
                foreach ($share->flowHistories as $fh) {
                    $fh->delete();
                }
            }

            $share->delete();
            DB::commit();

            return response()->json(['message' => 'Share deleted successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * @throws Throwable
     */
    public function review(Share $share)
    {
        $data = \request()->validate([
            'status' => ['required', 'in:' . Status::Approved . ',' . Status::Rejected],
            'comment' => ['required']
        ]);
        DB::beginTransaction();
        // Update the share's status
        $share->status = $data['status'];
        // Record who reviewed it and when
        $share->reviewed_by = auth()->id();
        $share->reviewed_at = now();
        $share->save();

        // Log the status change itself as a flow history item
        $share->flowHistories()->create([
            'user_id' => auth()->id(),
            'status' => $data['status'],
            'is_comment' => false, // This is a system action, not a user comment
            'comment' => "Share Status Changed to " . $data['status']
        ]);

        // Log the review comment as a separate flow history item
        $share->flowHistories()->create([
            'user_id' => auth()->id(),
            'comment' => $data['comment'],
            'is_comment' => true, // Mark this as a comment/review entry
        ]);

        if ($data['status'] === Status::Approved && $share->payment) {
            $share->payment->status = Status::Paid;
            $share->payment->save();
        }
        DB::commit();

        return redirect()->back()->with('success', 'Share has been reviewed successfully.');
    }

}
