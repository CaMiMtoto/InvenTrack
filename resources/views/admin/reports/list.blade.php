@extends('layouts.master')
@section('title', 'System Reports')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <x-toolbar title="System Reports" :breadcrumbs="[['label' => 'Reports']]"/>
        @can(\App\Constants\Permission::CREATE_REPORT)
            <button type="button" class="btn btn-primary btn-sm" id="addBtn">
                New Report
            </button>
        @endcan

    </div>

    <div class="my-3">
        <div class="table-responsive">
            <table class="table ps-2 align-middle  rounded table-row-dashed fs-6 gy-2" id="myTable">
                <thead>
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <th>Created At</th>
                    <th>Name</th>
                    <th>View Name</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="text-gray-600 fw-semibold"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="myModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Report
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="{{ route('admin.reports.store') }}" id="submitForm" method="post">
                    @csrf
                    <input type="hidden" id="id" name="id" value="0"/>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder=""/>
                        </div>
                        <div class="mb-3">
                            <label for="view_name" class="form-label">View Name</label>
                            <input type="text" class="form-control" id="view_name" name="view_name" placeholder=""/>
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

            let myTable = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! request()->fullUrl() !!}",
                columns: [
                    {data: 'created_at'},
                    {data: 'name'},
                    {data: 'view_name'},
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],
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
            $('#addBtn').click(function () {
                $('#myModal').modal('show');
            });
            $('#submitForm').on('submit', function (e) {
                e.preventDefault();
                let $form = $(this);
                let $submitBtn = $form.find('button[type="submit"]');
                let originalBtnText = $submitBtn.html();

                // Disable button and show loader
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

                // Clear previous validation errors
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            text: response.message || "Report created successfully!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                        // redirect to redirect_url of response
                        window.location.href = response.redirect_url;
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Handle validation errors
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let field = $form.find('[name="' + key + '"]');
                                field.addClass('is-invalid');
                                field.closest('.mb-3').append('<div class="invalid-feedback">' + value[0] + '</div>');
                            });
                            Swal.fire({
                                text: "Sorry, it looks like there are some errors detected, please try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
                            });
                        } else {
                            // Handle other server errors
                            Swal.fire({
                                text: "An unexpected error occurred. Please try again.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
                            });
                        }
                    },
                    complete: function () {
                        // Re-enable the button and restore its original text
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                    }
                });
            });

        });
    </script>
@endpush
