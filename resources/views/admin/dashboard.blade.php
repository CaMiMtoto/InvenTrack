@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
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
                        <!--begin::Row-->
                        <div class="row g-3 g-lg-6">
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-warning-subtle card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <span class="text-warning">
                                    <x-lucide-phone-call class="text-warning tw-h-5 tw-w-5"/>
                                </span>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-warning-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['utilities']['airtime_sold']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-warning-emphasis fw-semibold fs-5">
                                            Airtime Sold
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-danger-subtle card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <span class="text-warning">
                                    <x-lucide-zap class="text-danger tw-h-5 tw-w-5"/>
                                </span>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-danger-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['utilities']['cash_power_sold']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-danger-emphasis fw-semibold fs-5">
                                            Electricity Sold
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-success-subtle  card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <x-lucide-droplets class="text-success tw-h-5 tw-w-5"/>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-success-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['utilities']['water_payments']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-success-emphasis fw-semibold fs-5">
                                            Water Payments
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-primary-subtle  card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <x-lucide-wallet-cards class="text-primary tw-h-5 tw-w-5"/>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-primary-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['utilities']['utility_balance']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-primary-emphasis fw-semibold fs-5">
                                            Utility Balance
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                        </div>
                        <!--end::Row-->
                        <!--begin::Row-->
                        <div class="row mt-2 g-3 g-lg-6">
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-warning-subtle  card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <span class="text-warning">
                                    <x-lucide-credit-card class="text-warning tw-h-5 tw-w-5"/>
                                </span>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-warning-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['payments']['payments_received']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-warning-emphasis fw-semibold fs-5">
                                            Payments Received
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-danger-subtle  card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <span class="text-warning">
                                    <x-lucide-trending-up class="text-danger tw-h-5 tw-w-5"/>
                                </span>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-danger-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['payments']['payments_disbursed']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-danger-emphasis fw-semibold fs-5">
                                           Payments Disbursed
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-success-subtle  card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <x-lucide-send-to-back class="text-success tw-h-5 tw-w-5"/>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-success-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['payments']['settlements']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-success-emphasis fw-semibold fs-5">
                                           Settlements
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <!--begin::Items-->
                                <div
                                    class="bg-primary-subtle card card-body px-6 py-5">
                                    <!--begin::Symbol-->
                                    <x-lucide-wallet-cards class="text-primary tw-h-5 tw-w-5"/>
                                    <!--begin::Stats-->
                                    <div class="m-0">
                                        <!--begin::Number-->
                                        <h1 class="text-primary-emphasis fw-bold  lh-1 ls-n1 mb-1  my-4">
                                            {{ number_format($summary['payments']['payment_balance']) }}
                                        </h1>
                                        <!--end::Number-->

                                        <!--begin::Desc-->
                                        <span class="text-primary-emphasis fw-semibold fs-5">
                                            Payments Balance
                                        </span>
                                        <!--end::Desc-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Items-->
                            </div>
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Stats-->
                </div>
                <!--end::Body-->
            </div>

            <div class="row">
                <div class="col-lg-12 my-2 ">
                    <div class="card bg-light h-100">
                        <div class="card-body">
                            <h4>
                                Utilities Sales Chart (Last 7 Days)
                            </h4>
                            <canvas id="utilitiesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 my-2 ">
                    <div class="card bg-light h-100 card-body">
                        <h4>Payments Received vs Disbursed</h4>
                        <canvas id="paymentsChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-4 my-2 ">
                    @if ($topMerchants)
                        <div class="card bg-light h-100 card-body">
                            <h4>Top 5 Merchants (Sales)</h4>
                            <canvas id="merchantsPie"></canvas>
                        </div>

                    @endif
                </div>
            </div>

            <!-- Table -->


        </div>

        <!--end::Content-->
    </div>
@endsection

@push('scripts')
    {{--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}

    <script>
        const ctx = document.getElementById('utilitiesChart').getContext('2d');

        const utilitiesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Airtime',
                        data: {!! json_encode($chartData['airtime']) !!},
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 123, 255, 0.9)',
                        fill: true,
                        borderRadius: 2,
                        barThickness: 10,
                    },
                    {
                        label: 'Water',
                        data: {!! json_encode($chartData['water']) !!},
                        borderColor: 'green',
                        backgroundColor: 'rgba(40, 167, 69, 0.9)',
                        fill: true,
                        borderRadius: 2,
                        barThickness: 10,
                    },
                    {
                        label: 'Cash Power',
                        data: {!! json_encode($chartData['electricity']) !!},
                        borderColor: 'orange',
                        backgroundColor: 'rgba(255, 193, 7, 0.9)',
                        fill: true,
                        borderRadius: 2,
                        barThickness: 10,
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    title: {
                        display: true,
                        text: 'Utilities Sold Per Day'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const flowCtx = document.getElementById('paymentsChart').getContext('2d');
        const paymentsChart = new Chart(flowCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($flowChartData['labels']) !!},
                datasets: [
                    {
                        label: 'Received',
                        data: {!! json_encode($flowChartData['received']) !!},
                        backgroundColor: 'rgba(0, 128, 0, 0.6)',
                        borderRadius: 2,
                        barThickness: 10,
                    },
                    {
                        borderRadius: 2,
                        barThickness: 10,
                        label: 'Disbursed',
                        data: {!! json_encode($flowChartData['disbursed']) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {display: true, text: 'Daily Payments vs Disbursements'},
                },
                scales: {
                    y: {beginAtZero: true}
                }
            }
        });

        let elementById = document.getElementById('merchantsPie');
        if (elementById) {
            const pieCtx = elementById.getContext('2d');

            const merchantPie = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_column($topMerchants, 'name')) !!},
                    datasets: [{
                        data: {!! json_encode(array_column($topMerchants, 'total')) !!},
                        backgroundColor: ['#007bff', '#ffc107', '#28a745', '#dc3545', '#6f42c1'],
                        borderColor: 'transparent',
                    }],
                },
                options: {
                    plugins: {
                        title: {display: true, text: 'Top Merchants by Utility Sales'},
                        legend: {display: true, position: 'bottom'},
                    },
                    responsive: true,
                }
            });
        }

    </script>
@endpush
