@extends('layouts.master')
@section('title', 'Order Details')
@section('content')
    <!--begin::Toolbar-->
    {{--<a href="{{  }}" target="_blank"
       class="btn btn-sm btn-danger">
        <i class="bi bi-file-pdf"></i>
        Print Order
    </a>--}}
    <x-toolbar title="Order Details"
               :breadcrumbs="[
    ['label' => 'Orders', 'url' => route('admin.orders.index')],
    ['label' => 'Details']
]"
               :actions="[
    ['url'=>route('admin.orders.print',encodeId($saleOrder->id)),'label'=>'Print Order','icon'=>'<i class=\'bi bi-file-pdf\'></i>','class'=>'btn-danger']
]"
    />
    <!--end::Toolbar-->
    <x-order-details :saleOrder="$saleOrder"/>
    {{--    review section--}}

    {{-- Approval Section --}}
    @if(auth()->user()->can(\App\Constants\Permission::APPROVE_ORDERS) && strtolower($saleOrder->status)=='pending')
        <div class="card mt-4">
            <div class="card-body">
                <div>
                    <h1>Approval</h1>
                    <p>
                        Please review the order details above and choose to approve or reject this order. You may also
                        leave
                        a comment for additional context.
                    </p>
                </div>
                <form action="{{ route('admin.orders.update-status', encodeId($saleOrder->id)) }}" method="POST"
                      id="submitDecisionForm">
                    @csrf
                    @method('PATCH')

                    {{-- Comment --}}
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment (optional)</label>
                        <textarea name="comment" id="comment" rows="3" class="form-control"
                                  placeholder="Leave a note for approval/rejection..."></textarea>
                        @error('comment')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex gap-2">
                        <button type="submit" name="action" value="approved" class="btn btn-success"
                                data-decion_text="Are you sure you want to approve this order?">
                            Approve
                        </button>
                        <button type="submit" name="action" value="rejected" class="btn btn-danger"
                                data-decion_text="Are you sure you want to reject this order?">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <script>
        $(function () {
            let actionValue = null;
            let decisionText = null;

            // Track which button was clicked
            $('#submitDecisionForm button[type="submit"]').on('click', function (e) {
                actionValue = $(this).val();
                decisionText = $(this).data('decion_text');
            });

            $('#submitDecisionForm').on('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Confirm',
                    text: decisionText || 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = $(this);
                        let formData = form.serializeArray();
                        // Ensure action is included
                        formData.push({name: 'action', value: actionValue});

                        $.ajax({
                            url: form.attr('action'),
                            method: form.attr('method'),
                            data: $.param(formData),
                            headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                            success: function (response) {
                                Swal.fire('Success', 'Order status updated.', 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
