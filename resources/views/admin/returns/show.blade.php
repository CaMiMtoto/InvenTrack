@php use App\Constants\Permission;use App\Constants\Status; @endphp
@extends('layouts.master')
@section('title', 'Return Details')

@section('content')
    <x-toolbar title="Return Details" :breadcrumbs="[
        ['label' => 'Returns', 'url' => route('admin.returns.index')],
        ['label' => 'Details']
    ]"/>

    <div class="my-10">
        <div class="row mb-10">
            <div class="col-md-6 col-lg-4">
                <h6 class="text-muted">Order Number</h6>
                <p class="fw-bold">{{ $return->order->order_number }}</p>
                <h6 class="text-muted">Order Total</h6>
                <p class="fw-bold">{{ number_format($return->order->total_amount) }} RWF</p>
            </div>
            <div class="col-md-6 col-lg-4">
                <h6 class="text-muted">Customer Name</h6>
                <p class="fw-bold">{{ $return->order->customer->name }}</p>
                <h6 class="text-muted">Customer Address</h6>
                <p class="fw-bold">{{ $return->order->customer->address }}</p>
                <h6 class="text-muted">Customer Landmark</h6>
                <p class="fw-bold">{{ $return->order->customer->landmark??'N/A' }}</p>
            </div>
            <div class="col-md-6 col-lg-4">
                <h6 class="text-muted">Status</h6>
                <p>
                    <span class="badge bg-{{$return->status_color}}-subtle text-{{$return->status_color}} rounded-pill">{{ $return->status }}</span>
                </p>
                <h6 class="text-muted">Submitted By</h6>
                <p class="fw-bold">{{ $return->doneBy->name }} on {{ $return->created_at->format('d M Y, H:i') }}</p>
            </div>

        </div>
        <h4 class="mb-5">Returned Items</h4>
       <div class="card card-body border my-4">
           <div class="table-responsive">
               <table class="table table-row-dashed table-row-gray-300">
                   <thead>
                   <tr class="fw-bold fs-6 text-gray-800">
                       <th>Product</th>
                       <th>Quantity Returned</th>
                       <th>Return Reason</th>
                   </tr>
                   </thead>
                   <tbody>
                   @foreach($return->items as $item)
                       <tr>
                           <td>{{ $item->product->name }}</td>
                           <td>{{ $item->quantity }}</td>
                           <td>{{ $item->returnReason->name ?? 'N/A' }}</td>
                       </tr>
                   @endforeach
                   </tbody>
               </table>
           </div>
       </div>
        <div class="row">
            @if($return->approver)
                <div class="col-md-6 col-lg-4">
                    <h6 class="text-muted">Reviewed By</h6>
                    <p class="fw-bold">{{ $return->approver->name }}
                        on {{ $return->reviewed_at->format('d M Y, H:i') }}</p>
                </div>
            @endif
            <div class="col">
                <h6 class="text-muted">Submission Reason</h6>
                <p class="fw-bold">{{ $return->reason ?? 'N/A' }}</p>
            </div>
        </div>

        @if (strtolower($return->status) === Status::Pending && auth()->user()->can(Permission::APPROVE_RETURNED_ITEMS))
            <hr class="my-10">

            <form action="{{ route('admin.returns.review', encodeId($return->id)) }}" method="POST" id="reviewForm">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h4>Approval Section</h4>
                        <div class="row">
                            <div class="col-md-12 mb-5">
                                <label class="form-label required">Action</label>
                                <select name="status" id="status" class="form-select" >
                                    <option value=""></option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-5">
                                <label class="form-label">Comment</label>
                                <textarea name="comment" id="comment" class="form-control" rows="3"
                                          placeholder="A comment is required if you reject the return..."></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
/*
            $('#status').on('change', function () {
                if ($(this).val() === 'rejected') {
                    $('#comment').prop('required', true);
                    $('label[for="comment"]').addClass('required');
                } else {
                    $('#comment').prop('required', false);
                    $('label[for="comment"]').removeClass('required');
                }
            });
*/

            $('#reviewForm').on('submit',function (e) {
                e.preventDefault();
                let $form = $(this);
                let $submitBtn = $form.find('button[type="submit"]');
                let originalBtnText = $submitBtn.html();

                // Disable button and show loader
                $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');

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
                            text: response.message || "Return reviewed successfully!",
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
