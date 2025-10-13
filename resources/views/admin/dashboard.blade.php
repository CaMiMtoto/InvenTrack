@extends('layouts.master')
@section('title', 'Dashboard')
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
                <div>
                    <div class="d-flex justify-content-end gap-3">
                        <input type="date" class="form-control"
                               value=""/>
                        <input type="date" class="form-control"
                               value=""/>
                    </div>
                </div>
                <!--end::Actions-->
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div class="my-3">

            <livewire:dashboard.main-dashboard/>

        </div>

        <!--end::Content-->
    </div>
@endsection

@push('scripts')
    <script>
        // Sales Chart
        new Chart(document.getElementById("salesChart"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
                datasets: [{
                    label: "Sales",
                    data: [1200, 1500, 1100, 1800, 2000, 1700],
                    borderColor: "rgba(54, 162, 235, 1)",
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    tension: 0.4
                }]
            }
        });

        // Expenses Chart
        new Chart(document.getElementById("expensesChart"), {
            type: "bar",
            data: {
                labels: ["Rent", "Salaries", "Fuel", "Misc"],
                datasets: [{
                    label: "Expenses",
                    data: [500, 1200, 400, 300],
                    backgroundColor: ["#f44336", "#2196f3", "#ff9800", "#4caf50"]
                }]
            }
        });
    </script>
@endpush
