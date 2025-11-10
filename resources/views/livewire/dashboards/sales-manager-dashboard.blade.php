<div class="row">
    <div class="col-md-4">
        <div class="card card-flush mb-5 mb-xl-10 bg-primary-subtle text-primary-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($totalSales) }}"
                              class="fs-2hx fw-bold  text-primary-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($totalSales, 2) }}</span>
                    <span class="text-dark-emphasis pt-1 fw-semibold fs-6">Total Sales</span>
                </div>
                <x-lucide-shopping-bag class="tw-h-16 tw-w-16 text-primary-emphasis"/>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle text-success-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($totalSales) }}"
                              class="fs-2hx fw-bold  text-success-emphasis me-2 lh-1 ls-n2">{{ $salesGrowth }}%</span>
                    <span class="text-success-emphasis pt-1 fw-semibold fs-6">Sales Growth</span>
                </div>
                <x-lucide-trending-up class="tw-h-16 tw-w-16 text-success"/>
            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div class="card card-flush mb-5 mb-xl-10 bg-info-subtle text-info-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                            <span title="{{ number_format($newCustomers) }}"
                                  class="fs-2hx fw-bold text-info-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($newCustomers) }}</span>
                    <span class="text-info-emphasis pt-1 fw-semibold fs-6">New Customers</span>
                </div>
                <x-lucide-user-plus class="tw-h-16 tw-w-16 text-info"/>
            </div>
        </div>
    </div>
</div>
