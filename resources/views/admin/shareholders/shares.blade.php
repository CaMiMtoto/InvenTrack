@extends('layouts.master')
@section('title',"Shareholder's Shares")
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Toolbar-->
        <div class="d-flex justify-content-between flex-column flex-lg-row">
            <x-toolbar title="Shareholder's Shares" :breadcrumbs="[
    ['label'=>'Shareholders','url'=> route('admin.shareholders.index')],
    ['label'=>'Shares'],
]"/>
            <div>
                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                   data-bs-target="#kt_modal_add_share">
                    <x-lucide-plus class="tw-w-5 tw-h-5"/>
                    Add Share
                </a>
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Layout-->
                <div class="row">
                    <!--begin::Sidebar-->
                    <div class="col-lg-4 my-10">
                        <!--begin::Card-->
                        <div class="card mb-5 border  border-gray-300 mb-xl-8  h-100">
                            <!--begin::Card body-->
                            <div class="card-body">
                                <!--begin::Summary-->
                                <div class="d-flex flex-center flex-column mb-5">
                                    <!--begin::Name-->
                                    <a href="#"
                                       class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">{{ $shareholder->name }}</a>
                                    <!--end::Name-->
                                    <!--begin::Position-->
                                    <div class="fs-5 fw-bold text-muted mb-6">
                                        {{ $shareholder->legalType->name }} : {{ $shareholder->id_number }}
                                    </div>
                                    <!--end::Position-->
                                    <!--begin::Position-->
                                    <div class="fs-5 fw-bold text-muted mb-6">
                                        Address: {{  $shareholder->residential_address }}
                                    </div>
                                    <!--end::Position-->
                                </div>
                                <!--end::Summary-->
                                <!--begin::Details toggle-->
                                <div class="d-flex flex-stack fs-4 py-3">
                                    <div class="fw-bolder rotate collapsible"
                                         href="#kt_shareholder_view_details" role="button" aria-expanded="false"
                                         aria-controls="kt_shareholder_view_details">Details
                                        <span class="ms-2 rotate-180">
                                            <x-lucide-chevron-down class="tw-w-4 tw-h-4"/>
                                        </span>
                                    </div>
                                </div>
                                <!--end::Details toggle-->
                                <div class="separator"></div>
                                <!--begin::Details content-->
                                <div id="kt_shareholder_view_details" class="">
                                    <div class="py-5 fs-6">
                                        <div class="fw-bolder mt-5">Total Shares</div>
                                        <div
                                            class="text-gray-600">{{ number_format($shareholder->shares->sum('quantity')) }}</div>
                                        <div class="fw-bolder mt-5">Total Value</div>
                                        <div class="text-gray-600">{{-- Assuming a currency format --}}
                                            RWF {{ number_format($shareholder->shares->sum(function($share) { return $share->quantity * $share->value; }), 2) }}
                                        </div>
                                    </div>
                                </div>
                                <!--end::Details content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class=" col-lg-8 my-10">
                        <!--begin::Card-->
                        <div class="card mb-5 border  border-gray-300 mb-xl-8  h-100">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="fw-bolder">Shares List</h3>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0"
                                           id="kt_shares_table">
                                        <!--begin::Table head-->
                                        <thead>
                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                            <th>Created At</th>
                                            <th>#Shares</th>
                                            <th>Amount</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                                                                  <th class="text-end min-w-70px">Actions</th>
                                        </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody class="fw-bold text-gray-600">
                                        @forelse($shareholder->shares as $share)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($share->created_at)->format('d M, Y') }}</td>
                                                <td>{{ $share->quantity }}</td>
                                                <td>{{ number_format($share->value) }}</td>
                                                <td>{{ number_format($share->total) }}</td>
                                                <td>
                                                    <span class="badge badge-light-{{$share->statusColor}} rounded-pill">{{ ucfirst($share->status )}}</span>
                                                </td>
                                                  <td class="text-end">
                                                      <a href="" class="btn btn-sm btn-light-primary">
                                                          View
                                                      </a>
                                                  </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    No shares found for this shareholder.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Layout-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->

    {{--add new share modal--}}
    <div class="modal fade" id="kt_modal_add_share" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_share_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bolder">Add New Share</h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                      transform="rotate(-45 6 17.3137)" fill="currentColor"/>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                      fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <!--begin::Form-->
                <form id="kt_modal_add_share_form" class="form"
                      action="{{ route('admin.shareholders.shares.store', encodeId($shareholder->id)) }}">
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" name="shareholder_id" value="{{ $shareholder->id }}"/>
                        <div class="row">
                            <!--begin::Input group-->
                            <div class="col-md-6 mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Quantity</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" class="form-control" placeholder="Share Quantity"
                                       name="quantity"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="col-md-6 mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">Value</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" value="{{config('services.shares.value')}}" disabled
                                       class="form-control" placeholder="Share Value" name="value"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--begin::Input group-->
                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2" for="amount">Total Amount</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control" name="amount" id="amount" disabled/>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">Payment Type</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="payment_method_id" id="payment_method_id" class="form-select">
                                <option value="">Select Payment Method</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>

                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">Bank</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <select name="bank_id" id="bank_id" class="form-select">
                                <option value="">Select Bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2" for="reference_number">Reference Number</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control" name="reference_number" id="reference_number"/>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2" for="attachment">Attachment</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="file" class="form-control" name="attachment" id="attachment"/>
                            <!--end::Input-->
                        </div>


                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                    <div class=" modal-footer bg-light">
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                        </button>
                        <button type="reset" class="btn btn-secondary border" data-bs-dismiss="modal">Discard</button>
                    </div>
                </form>
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

@endsection

@push('scripts')
    <script>
        $(function () {
            // Function to calculate and update total amount
            function calculateTotalAmount() {
                const quantityInput = $('input[name="quantity"]');
                const valueInput = $('input[name="value"]');
                const amountInput = $('#amount');

                const quantity = parseFloat(quantityInput.val());
                // The value input is disabled, so its value is fixed.
                // We need to ensure it's parsed correctly.
                const shareValue = parseFloat(valueInput.val());

                if (!isNaN(quantity) && !isNaN(shareValue)) {
                    const totalAmount = quantity * shareValue;
                    amountInput.val(totalAmount.toFixed(2)); // Format to 2 decimal places for currency
                } else {
                    amountInput.val(''); // Clear if inputs are not valid numbers
                }
            }

            // Attach event listener to quantity input
            $('input[name="quantity"]').on('input', calculateTotalAmount);

            // Also, call it once when the modal is shown, in case of pre-filled values
            $('#kt_modal_add_share').on('shown.bs.modal', function () {
                calculateTotalAmount();
            });
            const $form = $('#kt_modal_add_share_form');

            $form.on('submit', function (e) {
                // Clear previous errors
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();


                e.preventDefault();
                const formData = new FormData(this);
                const url = $form.attr('action');
                const method = $form.attr('method');
                const submitBtn = $form.find('button[type="submit"]');
                submitBtn.prop('disabled', true);
                submitBtn.html('<span class="indicator-label">Submitting...</span>');
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(response => {
                        Swal.fire({
                            title: 'Success!',
                            text: response.data.message ?? "Record added successfully",
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    }).catch(error => {

                    // check for 422 validation errors
                    let message = 'An unexpected error occurred. Please try again.';
                    // Handle validation errors (status 422)
                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        // Create an HTML list of errors for the popup
                        const errorMessages = Object.values(errors).flat().map(function (msg) {
                            return `<li>${msg}</li>`;
                        }).join('');
                        message = `Please fix the following errors:<ul>${errorMessages}</ul>`;

                        // Display errors under form fields
                        $.each(errors, function (key, value) {
                            const input = $form.find(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.after(`<div class="invalid-feedback">${value[0]}</div>`);
                        });
                    } else {
                        // For non-validation errors, show a generic popup
                        Swal.fire({
                            title: 'Error!',
                            html: message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }).finally(() => {

                    // Re-enable the submit button and restore its text
                    submitBtn.prop('disabled', false);
                    submitBtn.html('<span class="indicator-label">Submit</span>');
                });
            })

        });


    </script>
@endpush
