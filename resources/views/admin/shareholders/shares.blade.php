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
                              <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_shares_table">
                                  <!--begin::Table head-->
                                  <thead>
                                  <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                      <th>Created At</th>
                                      <th>#Shares</th>
                                      <th>Amount</th>
                                      <th>Total</th>
                                      <th>Status</th>
{{--                                      <th class="text-end min-w-70px">Actions</th>--}}
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
                                          <td>{{ ucfirst($share->status )}}</td>
                                        {{--  <td class="text-end">
                                              --}}{{-- Actions dropdown for edit/delete share --}}{{--
                                          </td>--}}
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_add_share_form" class="form" action="{{ route('admin.shareholders.shares.store', encodeId($shareholder->id)) }}">
                        @csrf
                        <input type="hidden" name="shareholder_id" value="{{ $shareholder->id }}"/>
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">Quantity</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" class="form-control form-control-solid" placeholder="Share Quantity" name="quantity" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                         <!--begin::Input group-->
                         <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">Value</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="number" class="form-control form-control-solid" placeholder="Share Value" name="value" />
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

@endsection

@push('scripts')
    <script>
        "use strict";

        // Function to display errors
        function displayFormErrors(errors, form) {
            let errorString = 'Sorry, looks like there are some errors detected, please try again.';

            if (errors) {
                errorString = Object.values(errors)
                    .map(err => err.join(' '))
                    .join('<br/>');
            }

            Swal.fire({
                html: errorString,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: { confirmButton: "btn btn-primary" }
            });
        }

        // Class definition
        var KTModalAddShare = function () {
            var submitButton;
            var cancelButton;
            var validator;
            var form;
            var modal;
            var modalEl;

            // Init form inputs
            var handleForm = function () {
                // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
                validator = FormValidation.formValidation(
                    form,
                    {
                        fields: {
                            'quantity': {
                                validators: {
                                    notEmpty: {
                                        message: 'Quantity is required'
                                    }
                                }
                            },
                            'value': {
                                validators: {
                                    notEmpty: {
                                        message: 'Value is required'
                                    }
                                }
                            }
                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            bootstrap: new FormValidation.plugins.Bootstrap5({
                                rowSelector: '.fv-row',
                                eleInvalidClass: '',
                                eleValidClass: ''
                            })
                        }
                    }
                );

                // Revalidate on modal show
                modalEl.addEventListener('shown.bs.modal', function () {
                    validator.resetForm(true);
                });

                // Action buttons
                submitButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Validate form before submit
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status === 'Valid') {
                                submitButton.setAttribute('data-kt-indicator', 'on');
                                submitButton.disabled = true;

                                // Use fetch API to submit the form data
                                fetch(form.action, {
                                    method: 'POST',
                                    body: new FormData(form),
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                        'Accept': 'application/json',
                                    }
                                }).then(response => {
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;

                                    if (response.ok) {
                                        // Success message
                                        Swal.fire({
                                            text: "Form has been successfully submitted!",
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: { confirmButton: "btn btn-primary" }
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                modal.hide();
                                                location.reload(); // Reload the page to see the new share
                                            }
                                        });

                                    } else {
                                        // Display errors from server
                                        response.json().then(data => {
                                            displayFormErrors(data.errors, form);
                                        });
                                    }
                                }).catch(error => {
                                    submitButton.removeAttribute('data-kt-indicator');
                                    submitButton.disabled = false;
                                    Swal.fire({
                                        text: "Sorry, an unexpected error occurred. Please try again.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!", customClass: { confirmButton: "btn btn-primary" }
                                    });
                                });
                            } else {
                                displayFormErrors(null, form); // Display general validation errors
                            }
                        });
                    }
                });
            }

            return {
                // Public functions
                init: function () {
                    // Elements
                    modalEl = document.querySelector('#kt_modal_add_share');

                    if (!modalEl) {
                        return;
                    }

                    modal = new bootstrap.Modal(modalEl);

                    form = document.querySelector('#kt_modal_add_share_form');
                    submitButton = form.querySelector('button[type="submit"]');

                    handleForm();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTModalAddShare.init();
        });
    </script>
@endpush
