@extends('layouts.master')
@section('title', 'Payments')

@section('content')
    <!--begin::Toolbar-->
    <x-toolbar title="Payments"
               :breadcrumbs="[['label' => 'Payments']]"
               :actions="[
        ['url' => route('admin.payments.create'), 'label' => 'Add Payment', 'class' => 'btn-primary']
    ]"
    />
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div class="my-3">
        <div class="table-responsive">
            <table class="table ps-2 align-middle table-hover  rounded table-row-dashed fs-6 gy-3" id="myTable">
                <thead>
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <th>Payment Date</th>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th>Recorded By</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!--end::Content-->
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
                    {
                        data: 'paid_at',
                        name: 'paid_at',
                    },
                    {
                        data: 'order.order_number',
                        name: 'order.order_number',
                        orderable: false,
                    },
                    {
                        data: 'order.customer.name',
                        name: 'order.customer.name',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        searchable: false,
                    },
                    {
                        data: 'payment_method.name',
                        name: 'paymentMethod.name',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'reference_number',
                        name: 'reference_number',
                    },
                    {
                        data: 'user.name',
                        name: 'user.name',
                        orderable: false,
                        searchable: false,
                    },
                ],
                order: [[0, 'desc']],
                language: {
                    loadingRecords: '&nbsp;',
                    processing: '<div class="spinner spinner-primary spinner-lg mr-15"></div> Processing...'
                },
                "dom": "<'row'" +
                    "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l>" +
                    "<'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>" +
                    ">" +
                    "<'table-responsive'tr>" +
                    "<'row'" +
                    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                    ">"
            });
        });
    </script>
@endpush
