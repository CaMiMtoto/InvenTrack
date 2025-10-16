@extends('layouts.master')

@section('title', 'Stock Adjustment Details')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stock Adjustment Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Reason:</strong> {{ $stockAdjustment->reason }}</p>
                    <p><strong>Status:</strong> {{ $stockAdjustment->status }}</p>
                    <p><strong>Requested By:</strong> {{ optional($stockAdjustment->requester)->name }}</p>
                    @if ($stockAdjustment->approver)
                        <p><strong>Approved/Rejected By:</strong> {{ optional($stockAdjustment->approver)->name }}</p>
                    @endif
                </div>
            </div>

            <h4>Items</h4>
            <table class="table table-row-dashed table-row-gray-300 gy-7">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Quantity Before</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stockAdjustment->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->quantity_before }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($stockAdjustment->status === 'pending')
                <hr>
                <div class="d-flex justify-content-end">
                    <form action="{{ route('admin.stock-adjustments.reject', $stockAdjustment) }}" method="POST" class="me-2">
                        @csrf
                        <div class="mb-3">
                            <label for="rejection_reason">Rejection Reason</label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                    <form action="{{ route('admin.stock-adjustments.approve', $stockAdjustment) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
