<div>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <!--begin::Card widget 2-->
            <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle text-success-emphasis">
                <!--begin::Header-->
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($todaySales) }}"
                              class="fs-2hx fw-bold  text-success-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($todaySales, 2) }}</span>
                        <span class="text-dark-emphasis pt-1 fw-semibold fs-6">Weekly Sales</span>
                    </div>
                    <x-lucide-shopping-bag class="tw-h-16 tw-w-16 text-success-emphasis"/>
                </div>
            </div>
            <!--end::Card widget 2-->
            <!--begin::Card widget 2-->
            <div class="card card-flush mb-5 mb-xl-10  bg-info-subtle text-info-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-info-emphasis me-2 lh-1 ls-n2">{{ $pendingOrders }}</span>
                        <span class="text-dark-emphasis pt-1 fw-semibold fs-6">Pending Orders</span>
                    </div>
                    <x-lucide-shopping-cart class="tw-h-16 tw-w-16 text-success-emphasis"/>
                </div>
            </div>
            <!--end::Card widget 2-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
            <!--begin::Card widget 2-->
            <div class="card card-flush  mb-5 mb-xl-10  bg-danger-subtle text-danger-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-danger me-2 lh-1 ls-n2">{{ $stockAlerts }}</span>
                        <span class="text-danger-emphasis pt-1 fw-semibold fs-6">Stock Alerts</span>
                    </div>
                    <x-lucide-trending-down class="tw-h-16 tw-w-16 text-danger"/>
                </div>
            </div>
            <!--end::Card widget 2-->
            <!--begin::Card widget 2-->
            <div class="card card-flush  mb-5 mb-xl-10   bg-primary-subtle text-primary-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-primary me-2 lh-1 ls-n2">{{ $pendingDeliveries }}</span>
                        <span class="text-primary-emphasis pt-1 fw-semibold fs-6">Pending Deliveries</span>
                    </div>
                    <x-lucide-bike class="tw-h-16 tw-w-16 text-primary"/>
                </div>
            </div>
            <!--end::Card widget 2-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-lg-12 col-xl-12 col-xxl-6 mb-5 mb-xl-0">
            <!--begin::Chart widget 3-->
            <div class="card card-flush overflow-hidden h-md-100 border">

                <!--begin::Card body-->
                <div class="card-body px-0 d-flex justify-content-between flex-column">
                    <h3 class="card-title ps-8 align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Sales Performance</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">(Last 30 Days)</span>
                    </h3>
                    <div id="sales_overview_chart" class="ps-4 pe-6" style="max-height: 300px"></div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Chart widget 3-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row g-5 g-xl-10">
        <!--begin::Col-->
        <div class="col-xl-4">
            <!--begin::Chart Widget 1-->
            <div class="card card-xl-stretch mb-xl-10 border">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Order Status</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Current breakdown of all orders</span>
                    </h3>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div id="order_status_chart" style="height: 350px"></div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Chart Widget 1-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-xl-8">
            <!--begin::Chart Widget 1-->
            <div class="card card-xl-stretch mb-5 mb-xl-10 border">
                <!--begin::Header-->
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Top 5 Selling Products</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">This Month</span>
                    </h3>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body">
                    <div id="top_products_chart" style="height: 350px"></div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Chart Widget 1-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->

    <!--begin::Row-->
    <div class="row g-5 g-xl-10">
        <div class="col-xl-12">
            <!--begin::Chart widget 3-->
            <div class="card card-flush overflow-hidden h-md-100  border">
                <!--begin::Header-->
                <div class="card-header py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Financial Overview</span>
                        <span class="text-gray-400 mt-1 fw-semibold fs-6">Income vs Expenses (Last 6 Months)</span>
                    </h3>
                </div>
                <!--end::Header-->
                <!--begin::Card body-->
                <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                    <div id="income_vs_expense_chart" class="min-h-auto ps-4 pe-6" style="height: 300px"></div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Chart widget 3-->
        </div>
    </div>
</div>

@push('scripts')
    {{-- We are using ApexCharts, ensure it's loaded in your main layout --}}
    {{-- <script src="{{ asset('assets/plugins/custom/apexcharts/apexcharts.bundle.js') }}"></script> --}}
    <script>
        // Pass data from PHP to JavaScript
        const dashboardData = {
            salesChartData: @json($salesChartData),
            salesChartLabels: @json($salesChartLabels),
            orderStatusChart: @json($orderStatusChart),
            topProductsChart: @json($topProductsChart),
            incomeVsExpenses: @json($incomeVsExpenses)
        };
    </script>

    {{-- Load the dashboard JavaScript file --}}
    <script>
        "use strict";

        /**
         * Formats a number into a compact, human-readable string (e.g., 1500 -> "1.5K").
         * @param {number} num The number to format.
         * @param {number} digits The number of decimal places.
         * @returns {string} The formatted string.
         */
        function formatNumberToShort(num, digits = 1) {
            const lookup = [
                {value: 1, symbol: ""},
                {value: 1e3, symbol: "K"},
                {value: 1e6, symbol: "M"},
                {value: 1e9, symbol: "B"},
                {value: 1e12, symbol: "T"},
            ];
            const rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
            const item = lookup.slice().reverse().find(function (item) {
                return num >= item.value;
            });
            return item ? (num / item.value).toFixed(digits).replace(rx, "$1") + item.symbol : "0";
        }

        // Class definition
        var InvenTrackDashboard = function () {

            // Private functions
            const initSalesOverviewChart = function (data) {
                const element = document.getElementById('sales_overview_chart');
                if (!element) {
                    return;
                }

                const options = {
                    series: [{
                        name: 'Sales',
                        data: data.salesChartData // Use data object
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'area',
                        // height: 100,
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {},
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0,
                            stops: [0, 80, 100]
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        show: true,
                        width: 3,
                        colors: [KTUtil.getCssVariableValue('--bs-primary')]
                    },
                    xaxis: {
                        categories: data.salesChartLabels, // Use data object
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        tickAmount: 6,
                        labels: {
                            rotate: 0,
                            rotateAlways: false,
                            style: {
                                colors: KTUtil.getCssVariableValue('--bs-gray-800'),
                                fontSize: '12px'
                            }
                        },
                        crosshairs: {
                            position: 'front',
                            stroke: {
                                color: KTUtil.getCssVariableValue('--bs-primary'),
                                width: 1,
                                dashArray: 3
                            }
                        },
                        tooltip: {
                            enabled: true,
                            formatter: undefined,
                            offsetY: 0,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        tickAmount: 4,
                        labels: {
                            style: {
                                colors: KTUtil.getCssVariableValue('--bs-gray-800'),
                                fontSize: '12px'
                            },
                            formatter: function (val) {
                                return formatNumberToShort(val);
                            }
                        }
                    },
                    states: {
                        normal: {filter: {type: 'none', value: 0}},
                        hover: {filter: {type: 'none', value: 0}},
                        active: {allowMultipleDataPointsSelection: false, filter: {type: 'none', value: 0}}
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function (val) {
                                return new Intl.NumberFormat('en-US', {
                                    style: 'decimal',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(val);
                            }
                        }
                    },
                    colors: [KTUtil.getCssVariableValue('--bs-primary')],
                    grid: {
                        borderColor: KTUtil.getCssVariableValue('--bs-gray-200'),
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    markers: {
                        strokeColor: KTUtil.getCssVariableValue('--bs-primary'),
                        strokeWidth: 3
                    }
                };

                const chart = new ApexCharts(element, options);
                chart.render();
            }

            const initOrderStatusChart = function (data) {
                const element = document.getElementById('order_status_chart');
                if (!element) {
                    return;
                }

                const options = {
                    series: data.orderStatusChart.data, // Use data object
                    chart: {
                        fontFamily: 'inherit',
                        type: 'donut',
                        width: '350',
                    },
                    labels: data.orderStatusChart.labels, // Use data object
                    colors: [KTUtil.getCssVariableValue('--bs-warning'), KTUtil.getCssVariableValue('--bs-info'), KTUtil.getCssVariableValue('--bs-success'), KTUtil.getCssVariableValue('--bs-danger')],
                    legend: {
                        position: 'bottom'
                    },
                    responsive: [
                        {
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200
                                },
                            }
                        },
                    ]
                };

                const chart = new ApexCharts(element, options);
                chart.render();
            }

            const initTopProductsChart = function (data) {
                const element = document.getElementById('top_products_chart');
                if (!element) {
                    return;
                }

                const options = {
                    series: [{name: 'Quantity Sold', data: data.topProductsChart.data}], // Use data object
                    chart: {type: 'bar', height: 350, toolbar: {show: false}},
                    plotOptions: {bar: {borderRadius: 4, horizontal: true,}},
                    dataLabels: {enabled: false},
                    xaxis: {categories: data.topProductsChart.labels}, // Use data object
                    colors: [KTUtil.getCssVariableValue('--bs-primary')],
                };

                const chart = new ApexCharts(element, options);
                chart.render();
            }

            const initIncomeVsExpenseChart = function (data) {
                const element = document.getElementById('income_vs_expense_chart');
                if (!element) {
                    return;
                }

                const options = {
                    series: [
                        {name: 'Income', data: data.incomeVsExpenses.income}, // Use data object
                        {name: 'Expenses', data: data.incomeVsExpenses.expenses} // Use data object
                    ],
                    chart: {type: 'bar', height: 350, toolbar: {show: false}},
                    plotOptions: {bar: {horizontal: false, columnWidth: '55%', endingShape: 'rounded'}},
                    dataLabels: {enabled: false},
                    stroke: {show: true, width: 2, colors: ['transparent']},
                    xaxis: {categories: data.incomeVsExpenses.labels}, // Use data object
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                return '$' + formatNumberToShort(val);
                            }
                        }
                    },
                    fill: {opacity: 1},
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return '$' + new Intl.NumberFormat('en-US', {
                                    style: 'decimal',
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(val);
                            }
                        }
                    },
                    colors: [KTUtil.getCssVariableValue('--bs-success'), KTUtil.getCssVariableValue('--bs-danger')],
                };

                const chart = new ApexCharts(element, options);
                chart.render();
            }

            // Public methods
            return {
                init: function (dashboardData) { // Accept dashboardData as an argument
                    initSalesOverviewChart(dashboardData);
                    initOrderStatusChart(dashboardData);
                    initTopProductsChart(dashboardData);
                    initIncomeVsExpenseChart(dashboardData);
                }
            };
        }();

        // On document ready, initialize the dashboard and pass the data
        KTUtil.onDOMContentLoaded(function () {
            // The 'dashboardData' variable is expected to be defined globally by the Blade view
            InvenTrackDashboard.init(dashboardData);
        });
    </script>

@endpush
