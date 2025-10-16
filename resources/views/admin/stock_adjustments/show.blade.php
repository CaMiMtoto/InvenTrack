@extends('layouts.master')

@section('title', 'Stock Adjustment Details')

@section('content')
    <x-toolbar title="Stock Adjustments"
               :breadcrumbs="[
    ['label'=>'Stock Adjustments','url'=>route('admin.stock-adjustments.index')],
    ['label'=>'Details'],
]"

    />
    <div class="my-4">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Reason:</strong> {{ $stockAdjustment->reason }}</p>
                <p><strong>Status:</strong>
                    <span
                        class="badge bg-{{$stockAdjustment->status_color}}-subtle text-{{$stockAdjustment->status_color}} rounded-pill">{{ $stockAdjustment->status }}</span>
                </p>
                <p><strong>Requested By:</strong> {{ optional($stockAdjustment->requester)->name }}</p>
                @if ($stockAdjustment->approver)
                    <p><strong>Approved/Rejected By:</strong> {{ optional($stockAdjustment->approver)->name }}</p>
                @endif
            </div>
        </div>

        <h4>Items</h4>
        <table class="table table-row-dashed table-row-gray-300 gy-2">
            <thead>
            <tr class="fw-bolder fs-6 text-gray-600 text-uppercase">
                <th>Product</th>
                <th>Quantity</th>
                <th>Quantity Before</th>
                <th>Type</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($stockAdjustment->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->quantity_before }}</td>
                    <td>
                        <span
                            class="badge bg-{{$item->type=='increase'?'success':'danger'}}-subtle text-{{$item->type=='increase'?'success':'danger'}} rounded-pill">
                            {{ ucfirst($item->type) }}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if ($stockAdjustment->status === 'Pending' && auth()->user()->can(\App\Constants\Permission::APPROVE_STOCK_ADJUSTMENT))
            <hr>
            <h4>
                Approval Section
            </h4>
            <p>
                Please review above stock adjustment and take decision accordingly
            </p>

            <div class="row">
                <div class="col-md-8 col-lg-6">
                    <form action="{{ route('admin.stock-adjustments.review', encodeId($stockAdjustment->id)) }}"
                          method="POST" id="submitForm">
                        @csrf
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value=""></option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment">Comment</label>
                            <textarea name="comment" id="comment" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </form>

                </div>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#status').on('change', function () {
                if ($(this).val() === 'rejected') {
                    $('#comment').prop('required', true);
                } else {
                    $('#comment').prop('required', false);
                }
            });

            // Handle the review submission
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
                            text: response.message || "Adjustment reviewed successfully!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // Reload the page to show the updated status
                                window.location.reload();
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
                                field.closest('.form-select, .form-control').after('<div class="invalid-feedback">' + value[0] + '</div>');
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
