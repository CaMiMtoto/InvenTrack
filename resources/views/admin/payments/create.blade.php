@extends('layouts.master')

@section('title', 'Record New Payment')

@section('content')
    <x-toolbar title="Record New Payment" :breadcrumbs="[['label' => 'Record Payment']]"/>

    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="card">
            <div class="card-body">
                <!-- Order Search -->
                <div class="row mb-10">
                    <div class="col-md-6">
                        <label class="form-label">Search by Order Number</label>
                        <div class="input-group">
                            <input type="text" id="order_number_search" class="form-control" placeholder="Enter order number..."/>
                            <button class="btn btn-primary" type="button" id="search_order_btn">
                                <span class="indicator-label">Search</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <div id="search_error" class="invalid-feedback d-block"></div>
                    </div>
                </div>

                <!-- Order Details & Payment Form -->
                <div id="payment_section" class="d-none">
                    <form id="payment_form" action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div id="order_details_container">
                            {{-- Order details will be loaded here via AJAX --}}
                        </div>

                        <h3 class="mb-5">Payment Details</h3>

                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label required">Payment Date</label>
                                <input type="text" name="payment_date" id="payment_date" class="form-control" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Payment Method</label>
                                <select name="payment_method_id" class="form-select" data-control="select2" data-placeholder="Select a method" required>
                                    <option></option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Amount</label>
                                <input type="number" name="amount" class="form-control" step="any" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Reference</label>
                                <input type="text" name="reference" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Attachment (Optional)</label>
                                <input type="file" name="attachment" class="form-control"/>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-10">
                            <button type="submit" class="btn btn-primary">Record Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $("#payment_date").flatpickr({
                defaultDate: "today"
            });

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
                    data: { order_number: orderNumber },
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
        });
    </script>
@endpush