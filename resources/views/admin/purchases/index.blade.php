@extends('layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">Purchases</h2>
        <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary mb-3">+ New Purchase</a>

        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Invoice #</th>
                <th>Supplier</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->invoice_number }}</td>
                    <td>{{ $purchase->supplier?->name ?? 'N/A' }}</td>
                    <td>{{ $purchase->purchase_date }}</td>
                    <td>{{ number_format($purchase->total_amount, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $purchase->status === 'completed' ? 'success' : ($purchase->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.purchases.show', $purchase) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $purchases->links() }}
    </div>
@endsection
