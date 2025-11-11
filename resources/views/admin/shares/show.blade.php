@extends('layouts.master')
@section('title', 'Share Details - ' . $share->id)

@section('content')
    <div class="d-flex justify-content-between align-items-start">
        <x-toolbar title="Share Details" :breadcrumbs="[
        ['label' => 'Shares', 'url' => route('admin.shares.index')],
        ['label' => 'Details'],
    ]"/>
        <span
            class="badge badge-light-{{ $share->status_color }}">{{ ucfirst($share->status) }}</span>
    </div>

    <div class="my-10">
        <!--begin::Shareholder & Payment details-->
        <div class="row ">
            <div class="col-md-6 my-3">
                <div class="card border">
                    <div class="card-body">
                        <h4>Shareholder</h4>
                        <p class="fs-6 text-gray-700"><strong>Name:</strong> {{ $share->shareholder->name }}</p>
                        <p class="fs-6 text-gray-700"><strong>Email:</strong> {{ $share->shareholder->email }}</p>
                        <p class="fs-6 text-gray-700"><strong>ID Number:</strong>
                            <span>{{ $share->shareholder->legalType->name }}</span>
                            <span class="badge badge-light-info ms-2">{{ $share->shareholder->id_number }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 my-3">
                <div class="card border">
                    <div class="card-body">
                        <h4>Payment Details</h4>
                        @if($share->payment)
                            <p class="fs-6 text-gray-700">
                                <strong>Amount:</strong> {{ number_format($share->payment->amount, 2) }}
                                <span class="ms-3">
                                    {{ $share->payment->paymentMethod->name }}
                                </span>
                                @if($share->payment->bank)
                                    <span class="ms-3">{{ $share->payment->bank->name }}</span>
                                @endif
                            </p>
                            <p class="fs-6 text-gray-700">
                                <strong>Reference Number:</strong> {{ $share->payment->reference_number }}
                                <span class="fs-6 text-gray-700 ms-3"><strong>Status:</strong>
                                <span
                                    class="badge badge-light-{{ $share->payment->status_color }}">{{ ucfirst($share->payment->status) }}</span>
                                </span>
                            </p>

                            @if($share->payment->attachment)
                                <a href="{{ $share->payment->attachmentUrl }}" class="btn btn-light-danger btn-sm"
                                   target="_blank">
                                    <x-lucide-download-cloud class="tw-w-5 tw-h-5"/>
                                    Download Attachment
                                </a>
                            @endif
                        @else
                            <p class="fs-6 text-gray-500">No payment information available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body border my-3">
            <h4>
                Share Details
            </h4>
            <div class="row">
                <div class="my-3 col-md-6">
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Share Value</div>
                        <div class="d-flex align-items-center">
                            <span class="text-gray-900 fw-bolder fs-6">{{ number_format($share->value, 2) }}</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Quantity</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6">{{ $share->quantity }}</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Total Value</div>
                        <div class="d-flex align-items-senter">
                            <span class="text-gray-900 fw-bolder fs-6">{{ number_format($share->total, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="my-3 col-md-6">
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Status</div>
                        <div class="d-flex align-items-center">
                            <span
                                class="badge badge-light-{{ $share->status_color }}">{{ ucfirst($share->status) }}</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex flex-stack">
                        <div class="text-gray-700 fw-semibold fs-6 me-2">Created At</div>
                        <div class="d-flex align-items-senter">
                            <span
                                class="text-gray-900 fw-bolder fs-6">{{ $share->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>
                    @if($share->reviewed_at)
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Reviewed At</div>
                            <div class="d-flex align-items-senter">
                                <span
                                    class="text-gray-900 fw-bolder fs-6">{{ $share->reviewed_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @if($share->status === \App\Constants\Status::Pending && auth()->user()->can(\App\Constants\Permission::APPROVE_SHARES))
            <!--begin::Review Form-->
            <div class="row">
                <div class="col-md-8 col-lg-6">
                    <div>
                        <h4>Review Share</h4>
                        <p>
                            Please the details and make the correct decision about this share.
                        </p>
                        <form action="{{ route('admin.shares.review', encodeId($share->id)) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="status" class="form-label required">Action</label>
                                <select name="status" id="status" class="form-select" data-control="select2"
                                        data-placeholder="Select an action...">
                                    <option></option>
                                    <option value="{{ \App\Constants\Status::Approved }}">Approve</option>
                                    <option value="{{ \App\Constants\Status::Rejected }}">Reject</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="form-label required">Comment</label>
                                <textarea name="comment" id="comment" rows="4" class="form-control"></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--end::Review Form-->
        @endif

        <!--begin::History-->
        <div class="card card-flush">
            <div class="card-header">
                <h3 class="card-title">History</h3>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @forelse($share->flowHistories->sortByDesc('created_at') as $history)
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px me-4">
                                <div class="symbol-label bg-light-primary">
                                    <x-lucide-message-square-text class="fs-2 text-primary tw-w-5 tw-h-5"/>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-semibold mb-2">
                                        @if($history->is_comment)
                                            Comment by {{ $history->user->name ?? 'System' }}:
                                            <p class="text-muted fw-normal fs-6">{{ $history->comment }}</p>
                                        @else
                                            {{ $history->comment }}
                                            <span
                                                class="badge badge-light-{{$history->status_color}} ms-2">{{ ucfirst($history->status) }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div
                                            class="text-muted me-2 fs-7">{{ $history->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center">
                            <p class="text-muted fs-6">No history records found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!--end::History-->
    </div>
@endsection
