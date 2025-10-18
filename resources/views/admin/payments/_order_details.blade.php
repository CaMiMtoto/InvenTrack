<input type="hidden" name="order_id" value="{{ $order->id }}">

<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-10">
    <div class="d-flex flex-stack flex-grow-1">
        <div class="fw-semibold">
            <div class="fs-6 text-gray-700">
                <span>
                    <strong class="me-1">Customer:</strong> {{ $order->customer->name }} ,
                    <strong class="me-1">Phone:</strong> {{ $order->customer->phone }} ,
                    <strong class="me-1">Address:</strong> {{ $order->customer->address }} ,
                </span>

                <br>
                <strong class="me-1">Order Total:</strong> {{ number_format($order->total_amount, 2) }} RWF <br>
                <strong class="me-1">Total Paid:</strong> {{ number_format($order->tota_paid, 2) }} RWF
            </div>
        </div>
    </div>
</div>


<h3 class="mb-5">Payment Details</h3>
<form id="payment_form" action="{{ route('admin.payments.store',encodeId($order->id)) }}" method="POST"
      enctype="multipart/form-data">
    @csrf
    <div class="row g-5">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label ">Payment Date</label>
                <input type="date" value="{{ now()->toDateString() }}" name="payment_date" id="payment_date"
                       class="form-control" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label ">Payment Method</label>
                <select name="payment_method_id" class="form-select" data-control="select2"
                        data-placeholder="Select a method" >
                    <option></option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Amount Due</label>
                <input type="text" class="form-control bg-light" value="{{ number_format($order->amount_due, 2) }} RWF" readonly/>
                @if($order->is_fully_paid)
                    <div class="form-text text-success">This order is fully paid.</div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label ">Amount</label>
                <input type="number" name="amount" class="form-control" step="any" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Reference</label>
                <input type="text" name="reference" class="form-control"/>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Attachment (Optional)</label>
                <input type="file" name="attachment" class="form-control"/>
            </div>
        </div>
        <div class="col-md-12">
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end mt-10">
        <button type="submit" class="btn btn-primary">Record Payment</button>
    </div>
</form>
