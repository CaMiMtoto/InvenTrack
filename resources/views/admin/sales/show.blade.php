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
    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">
                <x-lucide-shopping-bag class="tw-h-5 tw-w-5"/>
                Details
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">
                <x-lucide-history class="tw-h-5 tw-w-5"/>
                History
            </a>
        </li>

    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
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
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
            <div class="card card-body">
                <ul class="tw-relative border-start tw-border-gray-200  tw-list-none">
                    @foreach($saleOrder->histories()->latest()->get() as $item)

                        <li class="tw-mb-10 tw-ms-6">
                        <span
                            class="tw-absolute tw-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 bg-{{ $item->statusColor }}-subtle text-{{ $item->statusColor }}-emphasis tw-rounded-full -tw-start-3 tw-ring-8 tw-ring-white">
                                <x-lucide-chevron-down class="tw-w-4 tw-h-4" aria-hidden="true"/>
                        </span>
                            <div
                                class=" tw-rounded-lg tw-shadow-xs ">
                                <div class="tw-items-center tw-justify-between tw-mb-3 sm:tw-flex">
                                    <time
                                        class="tw-mb-1 tw-text-sm tw-font-normal tw-text-gray-400 sm:tw-order-last sm:tw-mb-0">
                                        {{ $item->created_at->diffForHumans() }}
                                    </time>
                                    <div class=" tw-font-normal tw-text-gray-500 ">
                                        {{ $item->user->name }}
                                        <span
                                            class="bg-{{ $item->statusColor }}-subtle text-{{ $item->statusColor }}-emphasis  tw-text-sm  fw-bolder rounded-pill tw-me-2 tw-px-2.5 tw-py-0.5  tw-ms-3">
                                    {{ ucwords($item->status) }}
                                </span>
                                    </div>
                                </div>
                                <div
                                    class="tw-p-3 tw-text-sm tw-italic tw-font-normal tw-text-gray-500  border tw-border-gray-200 tw-rounded-lg tw-bg-gray-50 ">
                                    {{ $item->comment }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>

    </div>




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
