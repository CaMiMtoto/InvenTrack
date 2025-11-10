@extends('layouts.master')
@section('title',"Shareholders")
@section('content')
    <div>
        <div class="d-flex justify-content-between align-items-center">
            <x-toolbar title="Shareholders" :breadcrumbs="[['label'=>'Shareholders']]"/>
            @can(\App\Constants\Permission::MANAGE_SHAREHOLDERS)
                <button type="button" class="btn btn-sm btn-light-primary px-4 py-3" id="addBtn">
                    <i class="bi bi-plus fs-3"></i>
                    Add New
                </button>
            @endcan
        </div>

        <!--begin::Content-->
        <div class="my-3">
            <div class="table-responsive">
                <table
                    class="table ps-2 align-middle table-hover table-row-dashed table-row-gray-300 align-middle  fs-6 gy-1"
                    id="myTable">
                    <thead>
                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                        <th>Created At</th>
                        <th>Name</th>
                        <th>Legal Type</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Email</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--end::Content-->
    </div>

    <div class="modal fade" tabindex="-1" id="myModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Shareholder
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x fs-2"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form id="submitForm" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id" value="0"/>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="name" class="form-label ">Name</label>
                                <input type="text" class="form-control" id="name" name="name"/>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="legal_type_id" class="form-label ">Legal Type</label>
                                <select class="form-select" id="legal_type_id" name="legal_type_id"
                                        data-control="select2" data-dropdown-parent="#myModal">
                                    <option></option>
                                    @foreach($legalTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="id_number" class="form-label ">ID Number</label>
                                <input type="text" class="form-control" id="id_number" name="id_number"/>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="phone_number" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number"/>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"/>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="tin" class="form-label">TIN</label>
                                <input type="number" class="form-control" id="tin" name="tin"/>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="birth_date" class="form-label">Date Of Birth</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date"/>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="residential_address" class="form-label">
                                    Residential Address
                                </label>
                                <input type="text" class="form-control" id="residential_address"
                                       name="residential_address"/>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save changes</button>
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
            // Initialize DataTable
            let $myTable = $('#myTable');
            const table = $myTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.shareholders.index') }}",
                language: {
                    loadingRecords: '<span>&nbsp;</span>',
                    processing: '<div class="spinner spinner-primary spinner-lg mr-15"></div>Processing...'
                },
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'name', name: 'name'},
                    {
                        data: 'legal_type.name', name: 'legalType.name',
                        render: function (data, type, row) {
                            return `<div>
                            <em>${data}</em> <br>
                            <code class="px-0">${row.id_number}</code>
                            </div>`;
                        }
                    },
                    {data: 'phone_number', name: 'phone_number'},
                    {
                        data: 'shares_sum_value', name: 'shares_sum_value',
                        render: function (data, type, row) {
                            return Number(data).toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'RWF'
                            });
                        }, orderable: false, searchable: false
                    },
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                order: [[0, 'desc']],
                drawCallback: function () {
                    // Re-initialize tooltips
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            const myModal = new bootstrap.Modal(document.getElementById('myModal'));
            const $submitForm = $('#submitForm');
            const $modalTitle = $('.modal-title');
            const $saveBtn = $('#saveBtn');

            // Handle Add button click
            $('#addBtn').click(function () {
                $submitForm[0].reset();
                $('#id').val(0);
                $modalTitle.text('Add New Shareholder');
                $submitForm.find('.is-invalid').removeClass('is-invalid');
                $submitForm.find('.invalid-feedback').remove();
                $('#legal_type_id').val(null).trigger('change');
                myModal.show();
            });

            // Handle Edit button click
            $myTable.on('click', '.edit-btn', function () {
                // clear form fields
                $submitForm[0].reset();

                const url = $(this).data('url');
                $.get(url, function (data) {
                    $modalTitle.text('Edit Shareholder');
                    $submitForm.find('.is-invalid').removeClass('is-invalid');
                    $submitForm.find('.invalid-feedback').remove();
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#legal_type_id').val(data.legal_type_id).trigger('change');
                    $('#id_number').val(data.id_number);
                    $('#phone_number').val(data.phone_number);
                    $('#email').val(data.email);
                    $('#tin').val(data.tin);
                    $('#birth_date').val(data.birth_date);
                    $('#residential_address').val(data.residential_address);
                    myModal.show();
                });
            });

            // Handle form submission (Create and Update)
            $submitForm.on('submit', function (e) {
                e.preventDefault();
                $saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

                // Clear previous errors
                $submitForm.find('.is-invalid').removeClass('is-invalid');
                $submitForm.find('.invalid-feedback').remove();

                const id = $('#id').val();
                const url = id > 0 ? `{{ url('admin/shareholders') }}/${id}` : "{{ route('admin.shareholders.store') }}";
                const method = id > 0 ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    success: function (response) {
                        myModal.hide();
                        table.ajax.reload();
                        toastr.success(response.success);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                const field = $(`#${key}`);
                                field.addClass('is-invalid');
                                field.after(`<div class="invalid-feedback">${value[0]}</div>`);
                            });
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    },
                    complete: function () {
                        $saveBtn.prop('disabled', false).text('Save changes');
                    }
                });
            });

            // Handle Delete button click
            $myTable.on('click', '.delete-btn', function () {
                const url = $(this).data('url');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${name}". This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'DELETE',
                            data: {_token: '{{ csrf_token() }}'},
                            success: function (response) {
                                table.ajax.reload();
                                toastr.success(response.success);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
