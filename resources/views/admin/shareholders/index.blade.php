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
                    {{-- when editing we store the exact update URL (data-update_url) here so submission uses it --}}
                    <input type="hidden" id="update_url" name="update_url" value=""/>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="first_name" class="form-label ">First name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"/>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="last_name" class="form-label ">Last name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"/>
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
                            <div class="col-lg-6 mb-3">
                                <label for="province_id" class="form-label">Province</label>
                                <select class="form-select" id="province_id" name="province_id" data-control="select2"
                                        data-dropdown-parent="#myModal">
                                    <option></option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="district_id" class="form-label">District</label>
                                <select class="form-select" id="district_id" name="district_id" data-control="select2"
                                        data-dropdown-parent="#myModal">
                                    <option></option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="sector_id" class="form-label">Sector</label>
                                <select class="form-select" id="sector_id" name="sector_id" data-control="select2"
                                        data-dropdown-parent="#myModal">
                                    <option></option>
                                    @foreach($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="cell_id" class="form-label">Cell</label>
                                <select class="form-select" id="cell_id" name="cell_id" data-control="select2"
                                        data-dropdown-parent="#myModal">
                                    <option></option>
                                    @foreach($cells as $cell)
                                        <option value="{{ $cell->id }}">{{ $cell->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="village_id" class="form-label">Village</label>
                                <select class="form-select" id="village_id" name="village_id" data-control="select2"
                                        data-dropdown-parent="#myModal">
                                    <option></option>
                                    @foreach($villages as $village)
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                </select>
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

            // Preload address data for cascading selects
            const districts = @json($districts->map(function($d){ return ['id'=>$d->id,'name'=>$d->name,'province_id'=>$d->province_id]; }));
            const sectors = @json($sectors->map(function($s){ return ['id'=>$s->id,'name'=>$s->name,'district_id'=>$s->district_id]; }));
            const cells = @json($cells->map(function($c){ return ['id'=>$c->id,'name'=>$c->name,'sector_id'=>$c->sector_id]; }));
            const villages = @json($villages->map(function($v){ return ['id'=>$v->id,'name'=>$v->name,'cell_id'=>$v->cell_id]; }));

            function populateChild(selectSelector, items, parentKey, parentId) {
                let $sel = $(selectSelector);
                let currentVal = $sel.val();
                let options = '<option></option>';
                if (parentId) {
                    for (let i = 0; i < items.length; i++) {
                        if (String(items[i][parentKey]) === String(parentId)) {
                            options += `<option value="${items[i].id}">${items[i].name}</option>`;
                        }
                    }
                }
                $sel.html(options);
                // preserve selection when possible
                if (currentVal && $sel.find(`option[value="${currentVal}"]`).length) {
                    $sel.val(currentVal).trigger('change');
                } else {
                    $sel.val(null).trigger('change');
                }
            }

            // Cascade handlers: province -> districts -> sectors -> cells -> villages
            $('#province_id').on('change', function () {
                const pid = $(this).val();
                populateChild('#district_id', districts, 'province_id', pid);
                // clear downstream selects
                populateChild('#sector_id', [], 'district_id', null);
                populateChild('#cell_id', [], 'sector_id', null);
                populateChild('#village_id', [], 'cell_id', null);
            });

            $('#district_id').on('change', function () {
                const did = $(this).val();
                populateChild('#sector_id', sectors, 'district_id', did);
                populateChild('#cell_id', [], 'sector_id', null);
                populateChild('#village_id', [], 'cell_id', null);
            });

            $('#sector_id').on('change', function () {
                const sid = $(this).val();
                populateChild('#cell_id', cells, 'sector_id', sid);
                populateChild('#village_id', [], 'cell_id', null);
            });

            $('#cell_id').on('change', function () {
                const cid = $(this).val();
                populateChild('#village_id', villages, 'cell_id', cid);
            });

            // Handle Add button click
            $('#addBtn').click(function () {
                $submitForm[0].reset();
                $('#id').val(0);
                // ensure any previous update url is cleared when adding
                $('#update_url').val('');
                $modalTitle.text('Add New Shareholder');
                $submitForm.find('.is-invalid').removeClass('is-invalid');
                $submitForm.find('.invalid-feedback').remove();
                $('#legal_type_id').val(null).trigger('change');
                // clear address selects
                $('#province_id').val(null).trigger('change');
                $('#district_id').val(null).trigger('change');
                $('#sector_id').val(null).trigger('change');
                $('#cell_id').val(null).trigger('change');
                $('#village_id').val(null).trigger('change');
                // clear name fields
                $('#first_name').val('');
                $('#last_name').val('');
                myModal.show();
            });

            // Handle Edit button click
            $myTable.on('click', '.edit-btn', function () {
                // clear form fields
                $submitForm[0].reset();

                const url = $(this).data('url');
                // read update url provided on the action button (attribute name data-update_url)
                // use attr to read the raw attribute name (underscore) to be robust
                const updateUrl = $(this).attr('data-update_url') || '';
                $.get(url, function (data) {
                    $modalTitle.text('Edit Shareholder');
                    $submitForm.find('.is-invalid').removeClass('is-invalid');
                    $submitForm.find('.invalid-feedback').remove();
                    $('#id').val(data.id);
                    // store update url on the form so submit handler can use it
                    $('#update_url').val(updateUrl);
                    $('#first_name').val(data.first_name ?? '');
                    $('#last_name').val(data.last_name ?? '');
                    $('#legal_type_id').val(data.legal_type_id).trigger('change');
                    $('#id_number').val(data.id_number);
                    $('#phone_number').val(data.phone_number);
                    $('#email').val(data.email);
                    $('#tin').val(data.tin);
                    $('#birth_date').val(data.birth_date);
                    $('#residential_address').val(data.residential_address);
                    // populate address selects if available
                    $('#province_id').val(data.province_id ?? null).trigger('change');
                    $('#district_id').val(data.district_id ?? null).trigger('change');
                    $('#sector_id').val(data.sector_id ?? null).trigger('change');
                    $('#cell_id').val(data.cell_id ?? null).trigger('change');
                    $('#village_id').val(data.village_id ?? null).trigger('change');
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
                // prefer explicit update_url (set on edit) when available, otherwise construct from id
                const formUpdateUrl = $('#update_url').val();
                const url = id > 0
                    ? (formUpdateUrl && formUpdateUrl.length ? formUpdateUrl : `{{ url('admin/shareholders') }}/${id}`)
                    : "{{ route('admin.shareholders.store') }}";
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
