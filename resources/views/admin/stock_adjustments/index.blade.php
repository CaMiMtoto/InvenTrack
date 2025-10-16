@extends('layouts.master')

@section('title', 'Stock Adjustments')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Stock Adjustments</h3>
            <div class="card-toolbar">
                <a href="{{ route('admin.stock-adjustments.create') }}" class="btn btn-primary">New Adjustment</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-row-dashed table-row-gray-300 gy-7">
                <thead>
                    <tr class="fw-bolder fs-6 text-gray-800">
                        <th>ID</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Requested By</th>
                        <th>Approved By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->id }}</td>
                            <td>{{ $adjustment->reason }}</td>
                            <td>{{ $adjustment->status }}</td>
                            <td>{{ optional($adjustment->requester)->name }}</td>
                            <td>{{ optional($adjustment->approver)->name }}</td>
                            <td>
                                <a href="{{ route('admin.stock-adjustments.show', $adjustment) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
