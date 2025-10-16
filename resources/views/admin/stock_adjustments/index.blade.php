@extends('layouts.master')

@section('title', 'Stock Adjustments')

@section('content')
    <x-toolbar title="Stock Adjustments"
               :breadcrumbs="[
    ['label'=>'Stock Adjustment']
]"
               :actions="[
     !auth()->user()->can(\App\Constants\Permission::REQUEST_STOCK_ADJUSTMENT)? ['url'=>route('admin.stock-adjustments.create'),'label'=>'New Adjustment','class'=>'btn-primary']:null
]"
    />
    <div class="my-4">
        <table class="table table-row-dashed table-row-gray-300 gy-2" id="myTable">
            <thead>
            <tr class="fw-bolder fs-6 text-gray-600 text-uppercase">
                <th>Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Requested By</th>
                <th>Items</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            window.dt = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! request()->fullUrl() !!}',
                columns: [
                    {
                        data: 'created_at', name: 'created_at',
                        render: function (data, type, row, meta) {
                            return moment(data).format('MM/DD/YYYY');
                        }
                    },
                    {data: 'reason', name: 'reason'},
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row, meta) {
                            return `<span class="badge bg-${row.status_color}-subtle text-${row.status_color} rounded-pill">${data}</span>`;
                        }
                    },
                    {data: 'requester.name', name: 'requester.name'},
                    {data: 'items_count', name: 'items_count', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[0, 'desc']]
            });

        });
    </script>
@endpush
