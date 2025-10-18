@extends('layouts.master')

@section('title', 'Record New Payment')

@section('content')
    <x-toolbar title="Record New Payment" :breadcrumbs="[['label' => 'New Payment']]"/>


    <div>
        <!-- Order Search -->
        <div class="row mb-10">
            <div class="col-md-6">
                <label class="form-label">Search by Order Number</label>
                <div class="input-group">
                    <input type="text" id="order_number_search" class="form-control"
                           placeholder="Enter order number..."/>
                    <button class="btn btn-primary" type="button" id="search_order_btn">
                        <span class="indicator-label">Search</span>
                        <span class="indicator-progress">
                            Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
                <div id="search_error" class="invalid-feedback d-block"></div>
            </div>
        </div>

        <!-- Order Details & Payment Form -->
        <div id="payment_section" class="d-none">

                <div id="order_details_container">
                    {{-- Order details will be loaded here via AJAX --}}
                </div>


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
           /* $("#payment_date").flatpickr({
                defaultDate: "today"
            });*/

            const searchBtn = document.getElementById('search_order_btn');
            const ktSearch = new KTBlockUI(searchBtn);

            $('#search_order_btn').on('click', function () {
                let orderNumber = $('#order_number_search').val();
                let $errorDiv = $('#search_error');
                let $paymentSection = $('#payment_section');
                $errorDiv.text('');
                ktSearch.block();

                $.ajax({
                    url: '{{ route("admin.payments.search-order") }}',
                    type: 'GET',
                    data: {order_number: orderNumber},
                    success: function (response) {
                        $('#order_details_container').html(response);
                        $paymentSection.removeClass('d-none');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            $errorDiv.text(xhr.responseJSON.errors.order_number[0]);
                        } else {
                            $errorDiv.text('An unexpected error occurred.');
                        }
                        $paymentSection.addClass('d-none');
                    },
                    complete: function () {
                        ktSearch.release();
                    }
                });
            });

            $(document).on('submit','#payment_form',function (e) {
                e.preventDefault();
                let $form = $(this);
                let $submitBtn = $form.find('button[type="submit"]');
                let originalBtnText = $submitBtn.html();

                // Disable button and show loader
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Recording...');

                // Clear previous validation errors
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').remove();

                // Use FormData to handle file uploads
                let formData = new FormData(this);

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: formData,
                    processData: false, // Important for FormData
                    contentType: false, // Important for FormData
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            text: response.message || "Payment recorded successfully!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // Redirect to the order details page
                                window.location.href = response.redirect_url;
                            }
                        });
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Handle validation errors
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let field = $form.find('[name="' + key + '"]');
                                field.addClass('is-invalid');
                                field.closest('.form-control, .form-select').after('<div class="invalid-feedback">' + value[0] + '</div>');
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
