@extends('layouts.master')
@section('title', 'Sales Orders')
@section('content')
    <!--begin::Toolbar-->
    <x-toolbar title="Pending Deliveries"
    :breadcrumbs="[
    ['label'=>'Pending Delivery']
]"
    />
    <!--end::Toolbar-->
    <div class="my-3">


        <div class="">
            <div class="table-responsive">
                <table class="table ps-2 align-middle  rounded table-row-dashed table-row-gray-300 fs-6 gy-3"
                       id="myTable">
                    <thead>
                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                        <th style="width: 50px !important;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="check_all" id="check_all">
                            </div>
                        </th>
                        <th>Date</th>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
                <button class="btn btn-primary btn-sm" id="btnAssign" disabled>
                    Assign Selected
                </button>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="myModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Assign Delivery Person
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="{{ route('admin.products.categories.store') }}" id="submitForm" method="post">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" value="0"/>
                        <div class="mb-3">
                            <label for="delivery_person_id" class="form-label">Name</label>
                            <select name="delivery_person_id" class="form-select" id="delivery_person_id">
                                <option value="">Select Delivery Person</option>
                                @foreach($deliveryPersons as $deliveryPerson)
                                    <option value="{{ $deliveryPerson->id }}">{{ $deliveryPerson->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn bg-secondary text-light-emphasis" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
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
                    {
                        data: 'id', name: 'id', render: function (data, type, row, meta) {
                            return ` <div class="form-check">
                                <input class="form-check-input chec_order_id" value="${data}" type="checkbox" name="check_all" >
                            </div>`;
                        }, orderable: false, searchable: false
                    },
                    {
                        data: 'order_date', name: 'order_date'
                    },
                    {data: 'order_number', name: 'order_number'},
                    {data: 'customer.name', name: 'customer.name'},
                    {data: 'items_count', name: 'items_count', orderable: false, searchable: false},
                    {
                        data: 'items_sum_quantity_unit_price', name: 'items_sum_quantity_unit_price',
                        render: function (data, type, row, meta) {
                            return Number(data).toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'RWF'
                            });
                        },
                        orderable: false, searchable: false
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, 'desc']]
            });

            $(document).on('change', '#check_all', function () {
                let checked = $(this).is(':checked');
                $('.chec_order_id').prop('checked', checked);
                let checkedCount = $('.chec_order_id:checked').length;
                $('#btnAssign').prop('disabled', checkedCount === 0);
            });
            $('#btnAssign').on('click', function () {
                let selectedIds = $('.chec_order_id:checked').map(function () {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) return;

                Swal.fire({
                    title: 'Assign Orders',
                    text: `Assign ${selectedIds.length} selected order(s) to a delivery person?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, assign',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Pass IDs to modal (hidden input or JS variable)
                        $('#id').val(selectedIds.join(','));
                        $('#myModal').modal('show');
                    }
                });
            });

            $(document).on('change', '.chec_order_id', function () {
                let total = $('.chec_order_id').length;
                let checked = $('.chec_order_id:checked').length;
                $('#check_all').prop('checked', total === checked);
                $('#btnAssign').prop('disabled', checked === 0);
            });


        });
    </script>
@endpush
