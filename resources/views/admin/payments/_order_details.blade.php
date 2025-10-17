<input type="hidden" name="order_id" value="{{ $order->id }}">

<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-10">
    <div class="d-flex flex-stack flex-grow-1">
        <div class="fw-semibold">
            <div class="fs-6 text-gray-700">
                <strong class="me-1">Customer:</strong> {{ $order->customer->name }} <br>
                <strong class="me-1">Order Total:</strong> {{ number_format($order->total_price, 2) }} RWF <br>
                <strong class="me-1">Total Paid:</strong> {{ number_format($order->total_paid, 2) }} RWF
            </div>
        </div>
    </div>
</div>

<div class="mb-5">
    <label class="form-label">Amount Due</label>
    <input type="text" class="form-control bg-light" value="{{ number_format($order->amount_due, 2) }} RWF" readonly/>
    @if($order->is_fully_paid)
        <div class="form-text text-success">This order is fully paid.</div>
    @endif
</div>

<hr class="my-10">