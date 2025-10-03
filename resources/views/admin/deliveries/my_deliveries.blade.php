@extends('layouts.master')
@section('title',"My Deliveries")
@section('content')
    <x-toolbar title="Assigned To Me"
               :breadcrumbs="[
    ['label'=>'Deliveries']
]"
    />

    <div class="my-4">
        <div class="table-responsive">
            <table class="table ps-2 align-middle  rounded table-row-dashed fs-6  gy-2" id="myTable">
                <thead>
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <th>Created At</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            window.dt = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! request()->fullUrl() !!}",
                language: {
                    loadingRecords: '&nbsp;',
                    processing: '<div class="spinner spinner-primary spinner-lg mr-15"></div> Processing...'
                },
                columns: [
                    {
                        data: 'created_at', name: 'created_at',
                        render: function (data, type, row, meta) {
                            return moment(data).format('DD-MM-YYYY HH:mm');
                        }
                    },
                    {data: 'order.order_number', name: 'order.order_number'},
                    {data: 'order.customer.name', name: 'order.customer.name'},
                    {data: 'items_count', name: 'items_count', orderable: false, searchable: false},
                    {
                        data: 'order.total_amount',
                        name: 'order.total_amount',
                        render: function (data, type, row, meta) {
                            return Number(data).toLocaleString("en-US",{
                                style: 'currency',
                                currency: 'RWF'
                            })
                        }
                    },
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row, meta) {
                            return `<span class="badge bg-${row.status_color}-subtle text-${row.status_color} rounded-pill">${data}</span>`
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush
