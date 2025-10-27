@extends('layouts.master')
@section('title', 'Create Order')
@section('styles')
@endsection
@section('content')
    <div>
        <!--begin::Toolbar-->
        <x-toolbar
            :breadcrumbs="[
        ['label' => 'Sales Orders', 'url' => route('admin.orders.index')],
        ['label' => 'New Order', 'url' => '']
    ]"
            title="New Order"/>

        <!--end::Toolbar-->
        <!--begin::Content-->
        <form action="{{ route('admin.orders.store') }}" method="post" class="my-3" id="submitForm">
            @csrf

            <div class="row g-5">
                <div class="col-md-5 order-md-last">
                    <h4
                        class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-primary">Items</span>
                        <span class="badge bg-primary-subtle text-primary fw-bolder rounded-pill">
                                {{ \Cart::session(auth()->id())->getContent()->count() }}
                            </span>
                    </h4>
                    <p class="">
                        Below are list of products you are about to order
                    </p>
                    <ul class="list-group mb-3">
                        @foreach(\Cart::getContent() as $item)
                            <li class="list-group-item d-flex justify-content-between bg-transparent">
                                <div>
                                    <h6 class="">
                                        {{ $item->name }}
                                    </h6>
                                    <div>
                                        <span
                                            class="badge bg-secondary-subtle "> RF  {{ number_format($item->price) }}</span>
                                        <span>Available Qty:</span> {{ $item->associatedModel->stock }}
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="prices[]" value="{{$item->price}}">
                                    <input type="hidden" name="product_ids[]" value="{{$item->id}}">
                                    <div class="d-flex gap-2">
                                        <input class="form-control w-50px form-control-sm js-qty" type="number"
                                               name="quantities[]" data-original_qty="{{ $item->quantity }}"
                                               value="{{ $item->quantity }}" min="1"/>
                                        <a href="" class="btn btn-icon btn-sm btn-light-danger js-remove">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <ol class="list-group list-group-flush mt-10">
                        <li class="list-group-item d-flex justify-content-between align-items-start bg-transparent">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Subtotal</div>
                            </div>
                            <span class="fw-bolder">
                                  RF  <span
                                    id="subtotal">{{ number_format(\Cart::session(auth()->id())->getSubTotal()) }}</span>
                                </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start bg-transparent">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Shipping</div>
                            </div>
                            <span class="fw-bolder">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start bg-transparent">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    Order Total
                                </div>
                            </div>
                            <span class="fw-bolder">
                                   RF <span
                                    id="total">{{ number_format(\Cart::session(auth()->id())->getTotal()) }}</span>
                                </span>
                        </li>
                    </ol>
                </div>
                <div class="col-md-7">
                    <h4 class="mb-3">Order Information</h4>
                    <div class="row g-3">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label for="order_date" class="form-label">Order Date</label>
                                <input type="date" class="form-control" id="order_date" name="order_date" placeholder=""
                                       value="{{ now()->toDateString() }}"/>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select class="form-select" id="customer_id" name="customer_id">
                                <option value=""></option>
                                @foreach($customers as $item)
                                    <option value="{{$item->id}}"
                                            data-url="{{ route('admin.settings.customers.details',encodeId($item->id)) }}">
                                        {{$item->name}} - {{$item->address}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div id="customer_details_results" class="mt-10">
                        <!--begin::Alert-->
                        <div class="alert alert-info border">
                            <!--begin::Icon-->
                            <span class="svg-icon svg-icon-2hx svg-icon-info me-3">
                                    <x-lucide-info class="tw-h-10 tw-w-10"/>
                                </span>
                            <!--end::Icon-->

                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column">
                                <!--begin::Title-->
                                <h5 class="mb-1">
                                    Customer info
                                </h5>
                                <!--end::Title-->
                                <!--begin::Content-->
                                <span>
                                        This is where customer information wil show up.
                                    </span>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Alert-->
                    </div>
                    <button class="w-100 btn btn-primary btn-lg mt-10" type="submit">
                        Place Order
                    </button>
                </div>
            </div>


        </form>
        <!--end::Content-->
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {
            // Initialize Select2 on the customer dropdown
            let $customerId = $('#customer_id');

            $customerId.on('change', function (e) {
                const $selectedOption = $(this).find('option:selected');
                const url = $selectedOption.data('url');
                const $detailsContainer = $('#customer_details_results');

                // If a customer is selected (and not the placeholder)
                if (url) {
                    // Show a loading spinner
                    $detailsContainer.html('<div class="d-flex justify-content-center p-10"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

                    // Make the AJAX request
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function (response) {
                            // Replace the content with the response from the server
                            $detailsContainer.html(response);
                        },
                        error: function (xhr) {
                            // Handle errors, e.g., show an error message
                            console.error("Error fetching customer details:", xhr.responseText);
                            $detailsContainer.html('<div class="alert alert-danger">Could not load customer details. Please try again.</div>');
                        }
                    });
                } else {
                    // If the placeholder is selected, show the default info message
                    $detailsContainer.html(`
                        <div class="alert alert-info border">
                            <div class="d-flex flex-column">
                                <h5 class="mb-1">Customer info</h5>
                                <span>Select a customer to see their information.</span>
                            </div>
                        </div>
                    `);
                }
            });

            // Handle the order submission
            $('#submitForm').on('submit', function (e) {
                e.preventDefault();
                let $form = $(this);
                let $submitBtn = $form.find('button[type="submit"]');
                let originalBtnText = $submitBtn.html();

                // Disable button and show loader
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Placing Order...');

                // Clear previous validation errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            text: response.message || "Order placed successfully!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // Redirect to the orders index page
                                window.location.href = "{{ route('admin.orders.index') }}";
                            }
                        });
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Handle validation errors
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                let field = $('#' + key);
                                field.addClass('is-invalid');
                                field.closest('.form-control').after('<div class="invalid-feedback">' + value[0] + '</div>');
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
                        } else if (xhr.status === 400) {
                            let errorMessage = xhr.responseJSON.error ?? "An unexpected error occurred. Please try again.";
                            Swal.fire({
                                text: errorMessage,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-danger"
                                }
                            });
                        } else {
                            // Handle other errors
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
// Debounce function to prevent excessive AJAX calls
            const debounce = (func, delay) => {
                let timeout;
                return function (...args) {
                    const context = this;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), delay);
                };
            };
            const updateCartTotals = function (element) {
                let $this = $(element);
                let quantity = $this.val();
                let $listItem = $this.closest('li');
                let productId = $listItem.find('input[name="product_ids[]"]').val();

                // Ensure quantity is at least 1
                if (quantity < 1) {
                    quantity = 1;
                    $this.val(1);
                }
                const originalQty = $this.data('original_qty');
                $.ajax({
                    url: '{{ route("admin.orders.cart.update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#subtotal').text(response.subtotal);
                            $('#total').text(response.total);
                            $this.data('original_qty', quantity);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error updating cart quantity:', xhr.responseText);
                        // You could add a user-facing error message here if desired
                        if (xhr.status === 400) {
                            const message = xhr.responseJSON.message ?? "An unexpected error occurred, Please try again.";
                            Swal.fire({
                                text: message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                }
                            });
                        }
                        $this.val(originalQty);
                    }
                });
            };

            $(document).on('input', '.js-qty', debounce(function () {
                updateCartTotals(this);
            }, 500)); // 500ms delay

            $(document).on('click', '.js-remove', function (e) {
                e.preventDefault();
                let $this = $(this);
                let $listItem = $this.closest('li');
                let productId = $listItem.find('input[name="product_ids[]"]').val();

                // Add a loading spinner to the button
                $this.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                $.ajax({
                    url: '{{ route("admin.orders.cart.remove") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Update totals and item count
                            $('#subtotal').text(response.subtotal);
                            $('#total').text(response.total);
                            $('.badge.bg-primary-subtle').text(response.count);

                            // Remove the item from the list with an animation
                            $listItem.slideUp(function () {
                                $(this).remove();
                            });
                        }
                    },
                    error: function (xhr) {
                        console.error('Error removing item from cart:', xhr.responseText);
                        // Restore button icon on error
                        $this.html('<i class="bi bi-trash"></i>');
                    }
                });
            });

        });
    </script>
@endpush
