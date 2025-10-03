@extends('layouts.master')
@section('title', 'Products')
@section('styles')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
@endsection
@section('content')
    <div>
        <!--begin::Toolbar-->


        <x-toolbar title="Manage Products"
                   :breadcrumbs="[
        ['label' => 'Products', 'url' => '']
]"
                   :actions="[
                 ['label' => 'Add New', 'url' => '#', 'id'=>'addBtn', 'class'=>'btn-light-primary', 'icon'=>'<i class=\'bi bi-plus fs-4\'></i>'],
                 ['label' => 'Export Excel', 'url' => route('admin.products.excel-export'), 'class'=>'btn-success', 'icon'=>'<i class=\'bi bi-file-excel fs-4\'></i>']
]"
        />

        <!--end::Toolbar-->
        <!--begin::Content-->
        <div class="my-3">
            <div class="table-responsive">
                <table class="table ps-2 align-middle border rounded table-row-dashed fs-6 g-5" id="myTable">
                    <thead>
                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                        <th>Created At</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Reorder</th>
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
                        Product
                    </h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="bi bi-x"></i>
                    </div>
                    <!--end::Close-->
                </div>

                <form action="{{ route('admin.products.store') }}" id="submitForm" method="post">
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" value="0"/>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder=""/>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="price" class="form-label">Selling Price</label>
                                <input type="number" class="form-control" id="price" name="price" placeholder=""/>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="unit_measure" class="form-label">Unit Measure</label>
                                <input class="form-control" id="unit_measure" name="unit_measure" type="text"/>
                            </div>
                        </div>
                        <div class="row">


                            <div class="mb-3 col-md-6">
                                <label for="min_stock" class="form-label">
                                    Reorder Level
                                </label>
                                <input class="form-control" id="min_stock" name="min_stock" type="number"
                                       value="0"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <!--begin::Input group-->
                            <div class="fv-row">
                                <!--begin::Dropzone-->
                                <div class="dropzone rounded-1" id="productImagesDropzone">
                                    <!--begin::Message-->
                                    <div class="dz-message needsclick">
                                    <span class="text-primary">
                                           <x-lucide-upload-cloud class="tw-10 tw-h-10"/>
                                    </span>

                                        <!--begin::Info-->
                                        <div class="ms-4">
                                            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                                            <span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                </div>
                                <!--end::Dropzone-->
                            </div>
                            <!--end::Input group-->
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
        $(function () {
            Dropzone.autoDiscover = false;
            let uploadedImages = [];
            const myDropzone = new Dropzone("#productImagesDropzone", {
                url: "{{ route('admin.products.uploadTempImages') }}",
                paramName: "file",
                maxFilesize: 10,
                acceptedFiles: "image/*",
                addRemoveLinks: true,
                headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                success: function(file, response) {
                    uploadedImages.push(response.image_id);
                },
                removedfile: function(file) {
                    let index = uploadedImages.indexOf(file.upload?.response?.image_id);
                    if (index > -1) uploadedImages.splice(index, 1);
                    file.previewElement.remove();
                }
            });

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
                        render: function (data) {
                            return moment(data).format('DD-MM-YYYY HH:mm:ss');
                        }
                    },
                    {data: 'name', name: 'name'},
                    {data: 'category.name', name: 'category.name'},
                    {
                        data: 'price', name: 'price',
                        render: function (data, type, row) {
                            return Number(data).toLocaleString();
                        }
                    },
                    {
                        data: 'stock', name: 'stock',
                        render: function (data, type, row) {
                            return data + ' ' + (row.stock === null ? '' : row.unit_measure);
                        }
                    },
                    {
                        data: 'min_stock', name: 'min_stock',
                        render: function (data, type, row) {
                            const qty = row.stock;
                            const level = row.min_stock;
                            let color = '';
                            if (qty <= 0) {
                                color = 'danger';
                            } else if (qty <= level) {
                                color = 'warning';
                            } else {
                                color = 'success';
                            }
                            return `<span class="badge bg-${color}-subtle fs-7 fw-bolder text-${color} rounded-pill">${data}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        class: 'text-center',
                        width: '15%'
                    },
                ],
                order: [[0, 'desc']],
                lengthMenu: [10, 25, 50, 100, 500, 1000, 2000, 3000, 5000],
            });

            $('#addBtn').click(function () {
                $('#myModal').modal('show');
            });

            $('#myModal').on('hidden.bs.modal', function () {
                $('#submitForm').trigger('reset');
                $('#id').val(0);
                myDropzone.removeAllFiles(true);
                uploadedImages = []; // reset the array too
            });

            let submitForm = $('#submitForm');
            submitForm.submit(function (e) {
                e.preventDefault();
                let $this = $(this);
                let body = $this.serialize();
                uploadedImages.forEach(function(id) {
                    body += '&image_ids[]=' + id;
                });

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
                    data: body,
                    success: function (data) {
                        dt.ajax.reload();
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Record has been saved successfully.',
                            // showConfirmButton: false,
                            // timer: 1500
                        });

                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let $1 = $('#' + key);
                                $1.addClass('is-invalid');
                                // create span element under the input field with a class of invalid-feedback and add the error text returned by the validator
                                $1.parent().append('<span class="invalid-feedback">' + value[0] + '</span>');
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
                let id = $(this).data('id');
                let url = $(this).attr('href')
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $('#id').val(data.id);
                        $('#name').val(data.name);
                        $('#category_id').val(data.category_id);
                        $('#price').val(data.price);
                        $('#stock_quantity').val(data.stock_quantity);
                        $('#description').val(data.description);
                        $('#unit_measure').val(data.unit_measure);
                        $('#reorder_level').val(data.reorder_level);
                        $('#sold_in_square_meters').prop('checked', data.sold_in_square_meters);
                        if (data.sold_in_square_meters) {
                            $('#box_coverage_div').show();
                        } else {
                            $('#box_coverage_div').hide();
                        }
                        $('#box_coverage').val(data.box_coverage);
                        $('#stock_unit_measure').val(data.stock_unit_measure);
                        $('#myModal').modal('show');
                    }
                });
            });
        });
    </script>
@endpush
