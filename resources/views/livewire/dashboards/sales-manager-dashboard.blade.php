<div class="row">
    <div class="col-sm-6 col-md-3 col-xl">
        <div class="card card-flush mb-5 mb-xl-10 bg-primary-subtle text-primary-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($assignedOrders) }}"
                              class="fs-2hx fw-bold  text-primary-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($assignedOrders, 2) }}</span>
                    <span class="text-dark-emphasis pt-1 fw-semibold fs-6">Assigned Orders</span>
                </div>
                <x-lucide-user-check-2 class="tw-h-8 tw-w-8 text-primary-emphasis"/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3 col-xl">
        <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle text-success-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($deliveredOrders) }}"
                              class="fs-2hx fw-bold  text-success-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($deliveredOrders, 2) }}</span>
                    <span class="text-success-emphasis pt-1 fw-semibold fs-6">Delivered Orders</span>
                </div>
                <x-lucide-check-check class="tw-h-8 tw-w-8 text-success-emphasis"/>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3 col-xl">
        <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle text-success-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($partiallyDeliveredOrders) }}"
                              class="fs-2hx fw-bold  text-success-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($partiallyDeliveredOrders, 2) }}</span>
                    <span class="text-success-emphasis pt-1 fw-semibold fs-6">Partially Delivered Orders</span>
                </div>
                <x-lucide-check-circle class="tw-h-8 tw-w-8 text-success-emphasis"/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3 col-xl">
        <div class="card card-flush mb-5 mb-xl-10 bg-danger-subtle text-danger-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($returnedOrders) }}"
                              class="fs-2hx fw-bold  text-danger-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($returnedOrders, 2) }}</span>
                    <span class="text-danger-emphasis pt-1 fw-semibold fs-6">Returned Orders</span>
                </div>
                <x-lucide-refresh-ccw class="tw-h-8 tw-w-8 text-danger-emphasis"/>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-3 col-xl">
        <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle text-success-emphasis">
            <!--begin::Header-->
            <div class="card-body">
                <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($approvedShares) }}"
                              class="fs-2hx fw-bold  text-success-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($approvedShares, 2) }}</span>
                    <span class="text-success-emphasis pt-1 fw-semibold fs-6">Approved Shares</span>
                </div>
                <x-lucide-check-circle-2 class="tw-h-8 tw-w-8 text-success-emphasis"/>
            </div>
        </div>
    </div>

</div>
