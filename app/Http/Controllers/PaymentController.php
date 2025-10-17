<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function create()
    {
        $paymentMethods = PaymentMethod::query()->get();
        return view('admin.payments.create', compact('paymentMethods'));
    }

    public function searchOrder(Request $request)
    {
        $request->validate(['order_number' => 'required|string']);

        $order = Order::with('customer')
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            throw ValidationException::withMessages([
                'order_number' => 'Order not found.',
            ]);
        }

        return view('admin.payments._order_details', compact('order'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $order = Order::findOrFail($data['order_id']);

        if ($data['amount'] > $order->amount_due) {
            throw ValidationException::withMessages([
                'amount' => 'Payment amount cannot be greater than the amount due.',
            ]);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('payments', 'public');
        }

        $order->payments()->create([
            'payment_method_id' => $data['payment_method_id'],
            'amount' => $data['amount'],
            'payment_date' => $data['payment_date'],
            'reference' => $data['reference'],
            'notes' => $data['notes'],
            'attachment' => $attachmentPath,
            'user_id' => auth()->id(),
            'status' => 'completed',
        ]);

        // You can add a success message here
        return redirect()->route('admin.orders.show', $order)->with('success', 'Payment recorded successfully!');
    }
}
