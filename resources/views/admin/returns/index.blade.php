@extends('layouts.master')
@section('title', 'Product Returns')

@section('content')
    <x-toolbar title="Product Returns" :breadcrumbs="[['label' => 'Returns']]"/>

    <div class="my-3">
        <div class="table-responsive">
            <table class="table ps-2 align-middle  rounded table-row-dashed fs-6 gy-2" id="myTable">
                <thead>
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <th>Date</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Submitted By</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold"></tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let $myTable = $('#myTable');

            window.dt = $myTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! request()->fullUrl() !!}',
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'order.order_number', name: 'order.order_number'},
                    {data: 'order.customer.name', name: 'order.customer.name'},
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row) {
                            return `<span class="badge bg-${row.status_color}-subtle text-${row.status_color} rounded-pill">${data}</span>`;
                        }
                    },
                    {data: 'done_by.name', name: 'doneBy.name'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                order: [[0, 'desc']],
            });
        });
    </script>
@endpush
