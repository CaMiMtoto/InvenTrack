@extends('layouts.master')
@section('title', 'Product Classes')

@section('content')
    <div>
        <!--begin::Toolbar-->
        <x-toolbar title="Manage Product Classes" :breadcrumbs="[
        ['label' => 'Product Classes', 'url' => '']
    ]" :actions="[
                 ['label' => 'Add New', 'url' => '#', 'id'=>'addBtn', 'class'=>'btn-light-primary', 'icon'=>'<i class=\'bi bi-plus fs-4\'></i>']
            ]"/>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div class="my-3">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table ps-2 align-middle border rounded table-row-dashed fs-6 g-5" id="myTable">
                    <thead>
                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                        <th>Name</th>
                        <th>Rate (%)</th>
                        <th>Products</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
        </div>
        <!--end::Content-->
    </div>

    <!--begin::Modal - Add/Edit-->
    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h3 class="modal-title" id="modal_title">Product Class</h3>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <form id="submitForm" action="{{ route('admin.products.product-class.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="0"/>
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label for="name" class="form-label required">Product Class Name</label>
                            <input type="text" name="name" id="name"
                                   class="form-control mb-3 mb-lg-0"
                                   placeholder="Product class name"/>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-3">
                            <label for="rate" class="form-label required">Rate (%)</label>
                            <input type="number" name="rate" id="rate" step="0.01"
                                   class="form-control mb-3 mb-lg-0"
                                   placeholder="Rate"/>
                        </div>
                        <!--end::Input group-->
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Save changes</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="button" class="btn bg-secondary text-light-emphasis" data-bs-dismiss="modal">
                            Close
                        </button>

                    </div>
                </form>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add/Edit-->
@endsection

@push('scripts')
    <script>
        "use strict";
        $(function () {
            let table = $('#myTable');
            let datatable = table.DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.products.product-class.index') }}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'rate', name: 'rate'},
                    {
                        data: 'products_count',
                        name: 'products_count',
                        searchable: false,
                        orderable: false,
                        className: 'text-end'
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
                ]
            });

            const modalEl = document.getElementById('myModal');
            const modal = new bootstrap.Modal(modalEl);
            const form = $('#submitForm');
            const submitButton = form.find('[type="submit"]');

            $('#addBtn').on('click', function (e) {
                e.preventDefault();
                form.trigger('reset');
                form.find('#id').val(0);
                $('#modal_title').text('Add Product Class');
                modal.show();
            });

            table.on('click', '.js-edit', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        form.trigger('reset');
                        $('#modal_title').text('Edit Product Class');
                        form.find('#id').val(data.id);
                        form.find('#name').val(data.name);
                        form.find('#rate').val(data.rate);
                        modal.show();
                    },
                    error: function () {
                        Swal.fire('Error!', 'Failed to fetch product class details.', 'error');
                    }
                });
            });

            form.on('submit', function (e) {
                e.preventDefault();
                let $this = $(this);
                submitButton.attr('data-kt-indicator', 'on').prop('disabled', true);

                // Clear previous errors
                $this.find('.is-invalid').removeClass('is-invalid');
                $this.find('.invalid-feedback').remove();

                $.ajax({
                    url: $this.attr('action'),
                    type: 'POST',
                    data: $this.serialize(),
                    success: function (response) {
                        modal.hide();
                        datatable.ajax.reload();
                        Swal.fire('Success!', response.success, 'success');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let field = $('#' + key);
                                field.addClass('is-invalid');
                                field.parent().append('<div class="invalid-feedback">' + value[0] + '</div>');
                            });
                        } else {
                            Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                        }
                    },
                    complete: function () {
                        submitButton.removeAttr('data-kt-indicator').prop('disabled', false);
                    }
                });
            });

            table.on('click', '.js-delete', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                Swal.fire({
                    text: "Are you sure you want to delete this product class?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                datatable.ajax.reload();
                                Swal.fire('Deleted!', response.success, 'success');
                            },
                            error: function (xhr) {
                                let errorMsg = xhr.responseJSON.message || 'An error occurred.';
                                if (xhr.status === 409) { // Conflict
                                    errorMsg = 'Cannot delete this class as it is associated with products.';
                                }
                                Swal.fire('Error!', errorMsg, 'error');
                            }
                        });
                    }
                });
            });

            $(modalEl).on('hidden.bs.modal', function () {
                form.trigger('reset');
                form.find('#id').val(0);
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
            });
        });
    </script>
@endpush

