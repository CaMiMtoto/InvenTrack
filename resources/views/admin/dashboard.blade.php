@php use App\Constants\UserType; @endphp
@extends('layouts.master')

@section('content')
    @php
        $pageDescription = 'Overview &amp; Stats';
    @endphp
    <div>
        <!--begin::Toolbar-->
        <div class="mb-5">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-500">
                                <x-lucide-house class="fs-3 text-gray-400 me-n1 tw-h-5 tw-w-5"/>
                            </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Dashboard</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <x-lucide-chevron-right class="text-gray-400 mx-n1 tw-h-5 tw-w-5"/>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-gray-700">
                            Analytics
                        </li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                        Dashboard
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->


                <!--end::Actions-->
            </div>
        </div>

        <div>

            @if (auth()->user()->can(\App\Constants\Permission::VIEW_SALES_DASHBOARD))
                @livewire('dashboards.sales-manager-dashboard')
            @endif
            @if (auth()->user()->can(\App\Constants\Permission::VIEW_DELIVERY_DASHBOARD))
                @livewire('dashboards.delivery-person-dashboard')
            @endif
            @if (auth()->user()->can(\App\Constants\Permission::VIEW_STORE_KEEPER_DASHBOARD))
                @livewire('dashboards.store-keeper-dashboard')
            @endif
            @if (auth()->user()->can(\App\Constants\Permission::VIEW_CUSTOMER_CARE_DASHBOARD))
                @livewire('dashboards.customer-care-dashboard')
            @endif
            @if (auth()->user()->can(\App\Constants\Permission::VIEW_FINANCIAL_DASHBOARD))
                @livewire('dashboards.finance-user-dashboard')
            @endif
        </div>

    </div>
@endsection


