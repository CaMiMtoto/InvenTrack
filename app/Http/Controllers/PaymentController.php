<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Services\FlowHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;
use Yajra\DataTables\Exceptions\Exception;

class PaymentController extends Controller
{
    private FlowHistoryService $flowHistoryService;

    public function __construct()
    {
        $this->flowHistoryService = new FlowHistoryService();
    }

    public function create()
    {
        return view('admin.payments.create');
    }

    public function searchOrder(Request $request)
    {
        $request->validate(['order_number' => 'required|string']);

        $order = Order::with('customer')
            ->where('order_number', '=', $request->order_number)
            ->first();

        if (!$order) {
            throw ValidationException::withMessages([
                'order_number' => 'Order not found.',
            ]);
        }
        $paymentMethods = PaymentMethod::query()->get();
        return view('admin.payments._order_details', compact('order', 'paymentMethods'));
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);


        if ($data['amount'] > $order->amount_due) {
            throw ValidationException::withMessages([
                'amount' => 'Payment amount cannot be greater than the amount due.',
            ]);
        }
        DB::transaction(function () use ($data, $order, $request) {

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('payments');
            }

            $order->payments()->create([
                'payment_method_id' => $data['payment_method_id'],
                'amount' => $data['amount'],
                'paid_at' => $data['payment_date'],
                'reference_number' => $data['reference'],
                'notes' => $data['notes'],
                'attachment' => $attachmentPath,
                'user_id' => auth()->id(),
            ]);

            // check if the order amount has been fully paid
            $order->refresh();
            if ($order->is_fully_paid) {
                $order->update(['order_status' => Status::Paid]);
                $this->flowHistoryService->saveFlow(
                    $order->getMorphClass(),
                    $order->id,
                    "Order amount has been fully paid.",
                    Status::Paid
                );
            } else {
                $order->update(['order_status' => Status::PartiallyPaid]);
            }

        });
        // You can add a success message here
        if ($request->ajax()) {
            session()->flash('success', 'Payment recorded successfully!');
            return response()->json([
                'redirect_url' => route('admin.orders.show', encodeId($order->id)),
                'message' => 'Payment recorded successfully!',
                'data' => $order
            ], 200);
        }
        return redirect()
            ->route('admin.orders.show', encodeId($order->id))
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        $orderId = \request('order_id');

        if (\request()->ajax()) {
            $source = Payment::query()
                ->when($orderId, fn($query, $orderId) => $query->where([
                    ['paymentable_id', '=', decodeId($orderId)],
                    ['paymentable_type', '=', Order::class]
                ]))
                ->with(['order.customer', 'paymentMethod','user']);

            return datatables($source)
                ->make(true);

        }
        return view('admin.payments.index');
    }
}
