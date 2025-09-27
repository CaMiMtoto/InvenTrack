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

                <!--end::Actions-->
            </div>
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div class="my-3">

            <div class="card bg-light card-flush mb-3 h-xl-100 ">
                <!--begin::Heading-->
                <div
                    class="card-header rounded rounded-bottom-0 bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-end align-items-start">
                    <!--begin::Title-->
                    <div class="h4 card-title align-items-start flex-column pt-4">
                        <span class="fw-bold fs-2x mb-3">Overview</span>
                        <div class="fs-4">
                            Below are the statistics reported by the system.
                        </div>
                    </div>
                    <!--end::Title-->

                    <!--begin::Toolbar-->
                    <div class="card-toolbar pt-5">
                        <div class="d-flex justify-content-end gap-3">
                            <input type="date" class="form-control"
                                   value="{{$summary['from'] }}"/>
                            <input type="date" class="form-control"
                                   value="{{ $summary['to'] }}"/>
                        </div>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Heading-->

                <!--begin::Body-->
                <div class="card-body">

                    <!--begin::Stats-->
                    <div class="position-relative">
                        <!-- Stats Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-bg-primary shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Customers</h5>
                                        <p class="fs-4 fw-bold">1,250</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-bg-success shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">Orders</h5>
                                        <p class="fs-4 fw-bold">980</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-bg-warning shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">Deliveries</h5>
                                        <p class="fs-4 fw-bold">820</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-bg-danger shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">Returns</h5>
                                        <p class="fs-4 fw-bold">45</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Stats-->
                </div>
                <!--end::Body-->
            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Sales Overview</h5>
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Expenses Overview</h5>
                            <canvas id="expensesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

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
