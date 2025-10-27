@extends('layouts.master')
@section('title', 'Customers')
@section('content')
    <div>
        <div class="d-flex justify-content-between">
            <div>
                <!--begin::Toolbar-->
                <x-toolbar title="Manage Customers"
                           :breadcrumbs="[
                        ['label'=>'Customers']
                    ]"
                />
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-light-primary px-4 py-3" id="addBtn">
                    <i class="bi bi-plus fs-3"></i>
                    Add New
                </button>
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div class="my-3">
            <div class="table-responsive">
                <table class="table ps-2 align-middle border rounded table-row-dashed fs-6 g-5" id="myTable">
                    <thead>
                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                        <th>#</th>
                        <th>Created At</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
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
                        Customer
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="{{ route('admin.settings.customers.store') }}" id="submitForm" method="post"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="hidden" id="id" name="id" value="0"/>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder=""/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder=""/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder=""/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input class="form-control" id="address" name="address"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="landmark" class="form-label">Landmark</label>
                                    <input type="text" class="form-control" id="landmark" name="landmark"
                                           placeholder=""/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="nickname" class="form-label">Nickname</label>
                                    <input type="text" class="form-control" id="nickname" name="nickname"/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="district_id" class="form-label">District</label>
                                    <select name="district_id" id="district_id" class="form-select">
                                        <option value=""></option>
                                        @foreach($districts as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="sector_id" class="form-label">Sector</label>
                                    <select name="sector_id" id="sector_id" class="form-select">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="cell_id" class="form-label">Cell</label>
                                    <select name="cell_id" id="cell_id" class="form-select">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="village_id" class="form-label">Village</label>
                                    <select name="village_id" id="village_id" class="form-select">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="address_photo">Address Photo</label>
                                    <input type="file" class="form-control" id="address_photo" name="address_photo"/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label fs-6 fw-bold">Location</label>
                                        <button type="button" class="btn btn-sm btn-light-primary" id="getLocationBtn">
                                            <i class="bi bi-geo-alt-fill fs-7"></i> Get Current Location
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="number" step="any" class="form-control" id="latitude"
                                                   name="latitude" placeholder="Latitude"/>
                                        </div>
                                        <div class="col">
                                            <input type="number" step="any" class="form-control" id="longitude"
                                                   name="longitude" placeholder="Longitude"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        const getSectors = function (districtId, selectedId = null) {
            let url = '{{ route('districts.sectors',':id') }}';
            url = url.replace(':id', districtId);
            let $sectorElement = $('#sector_id');

            // Clear dependent dropdowns
            $sectorElement.empty().append('<option value=""></option>');
            $('#cell_id').empty().append('<option value=""></option>');
            $('#village_id').empty().append('<option value=""></option>');

            if (!districtId) return;

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $sectorElement.append('<option value="">Select Sector</option>');
                    $.each(data, function (index, sector) {
                        $sectorElement.append($('<option>', {
                            value: sector.id,
                            text: sector.name
                        }));
                    });

                    if (selectedId) {
                        $sectorElement.val(selectedId).trigger('change');
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching sectors:", xhr.responseText);
                }
            });
        };

        const getCells = function (sectorId, selectedId = null) {
            let url = '{{ route('sectors.cells',':id') }}';
            url = url.replace(':id', sectorId);
            let $cellElement = $('#cell_id');

            $cellElement.empty().append('<option value=""></option>');
            $('#village_id').empty().append('<option value=""></option>');

            if (!sectorId) return;

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $cellElement.append('<option value="">Select Cell</option>');
                    $.each(data, function (index, cell) {
                        $cellElement.append($('<option>', {
                            value: cell.id,
                            text: cell.name
                        }));
                    });

                    if (selectedId) {
                        $cellElement.val(selectedId).trigger('change');
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching cells:", xhr.responseText);
                }
            });
        };

        const getVillages = function (cellId, selectedId = null) {
            let url = '{{ route('cells.villages',':id') }}';
            url = url.replace(':id', cellId);
            let $villageElement = $('#village_id');

            $villageElement.empty().append('<option value=""></option>');

            if (!cellId) return;

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $villageElement.append('<option value="">Select Village</option>');
                    $.each(data, function (index, village) {
                        $villageElement.append($('<option>', {
                            value: village.id,
                            text: village.name
                        }));
                    });

                    if (selectedId) {
                        $villageElement.val(selectedId).trigger('change');
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching villages:", xhr.responseText);
                }
            });
        };


        $(function () {
            // Initialize Select2 on all select elements within the modal
            /*    $('#myModal').find('select').select2({
                    dropdownParent: $('#myModal'),
                    placeholder: "Select an option",
                    allowClear: true
                });*/

            // Event listeners for dropdown changes
            $('#district_id').on('change', function () {
                getSectors($(this).val());
            });

            $('#sector_id').on('change', function () {
                getCells($(this).val());
            });

            $('#cell_id').on('change', function () {
                getVillages($(this).val());
            });

            let myTable = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{!! request()->fullUrl() !!}",
                language: {
                    loadingRecords: '&nbsp;',
                    processing: '<div class="spinner spinner-primary spinner-lg mr-15"></div> Processing...'
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: 'text-center',
                        width: '15%'
                    },
                ],
                'order': [1, 'desc']
            });

            let $myModal = $('#myModal');
            let $submitForm = $('#submitForm');
            $('#addBtn').click(function () {
                $submitForm.trigger('reset');
                $('#id').val(0);
                // Reset select2 fields
                $myModal.find('select').val(null).trigger('change');
                $myModal.modal('show');
            });

            $myModal.on('hidden.bs.modal', function () {
                $submitForm.trigger('reset');
                $('#id').val(0);
                $myModal.find('select').val(null).trigger('change');
                // Clear validation errors
                $submitForm.find('.is-invalid').removeClass('is-invalid');
                $submitForm.find('.invalid-feedback').remove();
            });

            $submitForm.submit(function (e) {
                e.preventDefault();
                let $this = $(this);
                let formData = new FormData(this);
                let url = $this.attr('action');
                let btn = $(this).find('[type="submit"]');
                btn.prop('disabled', true);
                btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
                // remove the error text
                $this.find('.invalid-feedback').remove();
                // remove the error class
                $this.find('.is-invalid').removeClass('is-invalid');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        myTable.ajax.reload();
                        $myModal.modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Record has been saved successfully.',
                        });

                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let field = $('#' + key);
                                field.addClass('is-invalid');
                                field.parent().append('<span class="invalid-feedback">' + value[0] + '</span>');
                            });
                        } else if (xhr.status === 400) {
                            const message = xhr.responseJSON.message?? 'An unexpected error occurred.';
                           Swal.fire({
                               icon: 'error',
                               title: 'Error!',
                               text: message,
                           });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                            });
                        }
                    },
                    complete: function () {
                        btn.prop('disabled', false);
                        btn.html('Save changes');
                    }
                });
            });

            $(document).on('click', '.js-edit', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#id').val(data.id);
                        $('#name').val(data.name);
                        $('#email').val(data.email);
                        $('#phone').val(data.phone);
                        $('#address').val(data.address);
                        $('#landmark').val(data.landmark);
                        $('#nickname').val(data.nickname);

                        // Set district and trigger change to load sectors
                        $('#district_id').val(data.district_id);

                        // The dependent data will be loaded via chained AJAX calls
                        // We pass the IDs to pre-select them once the options are loaded
                        getSectors(data.district_id, data.sector_id);
                        getCells(data.sector_id, data.cell_id);
                        getVillages(data.cell_id, data.village_id);

                        $('#latitude').val(data.latitude);
                        $('#longitude').val(data.longitude);
                        $myModal.modal('show');
                    }
                });
            });

            const handleLocationPermission = function () {
                if ('permissions' in navigator) {
                    navigator.permissions.query({name: 'geolocation'}).then(function (permissionStatus) {
                        const $locationBtn = $('#getLocationBtn');
                        const $icon = $locationBtn.find('i');

                        if (permissionStatus.state === 'denied') {
                            $locationBtn.prop('disabled', true);
                            $icon.removeClass('bi-geo-alt-fill').addClass('bi-geo-alt');
                            $locationBtn.attr('data-bs-toggle', 'tooltip');
                            $locationBtn.attr('title', 'Location access is blocked in your browser settings.');
                            $locationBtn.tooltip();
                        } else {
                            $locationBtn.prop('disabled', false);
                            $icon.removeClass('bi-geo-alt').addClass('bi-geo-alt-fill');
                            if ($locationBtn.data('bs.tooltip')) {
                                $locationBtn.tooltip('dispose');
                            }
                        }
                        permissionStatus.onchange = handleLocationPermission;
                    });
                }
            };

            $('#getLocationBtn').on('click', function () {
                if (navigator.geolocation) {
                    let btn = $(this);
                    let originalText = btn.html();
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Getting location...');

                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            $('#latitude').val(position.coords.latitude.toFixed(7));
                            $('#longitude').val(position.coords.longitude.toFixed(7));
                            btn.prop('disabled', false).html(originalText);
                        },
                        function (error) {
                            let message = 'An unknown error occurred.';
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    message = "You denied the request for Geolocation.";
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    message = "Location information is unavailable.";
                                    break;
                                case error.TIMEOUT:
                                    message = "The request to get user location timed out.";
                                    break;
                            }
                            Swal.fire('Error!', message, 'error');
                            btn.prop('disabled', false).html(originalText);
                        }
                    );
                } else {
                    Swal.fire('Not Supported', 'Geolocation is not supported by this browser.', 'warning');
                }
            });

            $myModal.on('show.bs.modal', function () {
                handleLocationPermission();
            });

        });
    </script>
@endpush
