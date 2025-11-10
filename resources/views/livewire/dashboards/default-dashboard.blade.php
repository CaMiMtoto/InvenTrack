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
