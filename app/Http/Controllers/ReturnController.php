<?php

namespace App\Http\Controllers;

use App\Constants\Permission;
use App\Constants\Status;
use App\Models\ReturnModel;
use App\Services\FlowHistoryService;
use DB;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class ReturnController extends Controller
{
    public function __construct(private readonly FlowHistoryService $flowHistoryService)
    {
    }

    /**
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->input('status', '');
            $source = ReturnModel::with(['order.customer', 'doneBy'])
                ->withCount('items')
                ->when($status, function ($query, $status) {
                    $query->where('status', '=', $status);
                });

            return datatables($source)
                ->editColumn('created_at', fn(ReturnModel $r) => $r->created_at->format('d M, Y'))
                ->addColumn('action', fn(ReturnModel $r) => view('admin.returns.partials._actions', compact('r')))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.returns.index');
    }

    public function show(ReturnModel $return)
    {
        $return->load(['items.product', 'items.returnReason', 'order.customer', 'doneBy', 'approver']);
        return view('admin.returns.show', compact('return'));
    }

    /**
     * @throws Throwable
     */
    public function review(Request $request, ReturnModel $return)
    {
//        $this->authorize(Permission::APPROVE_RETURNED_ITEMS);

        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'comment' => ['nullable', 'required_if:status,rejected', 'string', 'max:500']
        ]);

        if (strtolower($return->status) !== Status::Pending) {
            if ($request->ajax()) {
                return response()->json(
                    ['message' => 'This return request has already been processed.'],
                    400
                );
            }
            return back()->with('error', 'This return request has already been processed.');
        }

        DB::transaction(function () use ($data, $return) {
            $newStatus = ($data['status'] === 'approved') ? Status::Approved : Status::Rejected;

            // If approved, return the items to stock.
            if ($newStatus === Status::Approved) {
                foreach ($return->items as $item) {
                    if ($item->returnReason->restockable) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            $return->update([
                'status' => $newStatus,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $this->flowHistoryService->saveFlow(
                $return->getMorphClass(),
                $return->id,
                $data['comment'] ?? "Return has been " . ucwords($newStatus),
                $newStatus
            );
        });
        if ($request->ajax()) {
            session()->flash('success', 'Return has been reviewed successfully.');
            return response()->json(['message' => 'Return has been reviewed successfully.']);
        }

        return redirect()->route('admin.returns.show', $return)
            ->with('success', 'Return has been reviewed successfully.');
    }
}
