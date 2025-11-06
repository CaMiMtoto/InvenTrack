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
                <div class="d-flex flex-column flex-lg-row">
                    <!--begin::Sidebar-->
                    <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px my-10">
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
                                            ${{ number_format($shareholder->shares->sum(function($share) { return $share->quantity * $share->value; }), 2) }}
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
                    <div class="flex-lg-row-fluid ms-lg-15 my-10">
                        <!--begin::Card-->
                        <div class="card mb-5 border  border-gray-300 mb-xl-8  h-100">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="fw-bolder">Shares List</h3>
                                </div>
                            </div>
                            <div class="card-body pt-0">
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
                                            <td>{{ $share->status }}</td>
                                            <td class="text-end">
                                                {{-- Actions dropdown for edit/delete share --}}
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

    @include('admin.shares.modals._add-share')
@endsection
