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
        if (\request()->ajax()) {
            return datatables(
                Share::query()
                    ->with(['shareholder'])
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
