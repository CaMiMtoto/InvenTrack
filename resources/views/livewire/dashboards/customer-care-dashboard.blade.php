<div class="mt-5">
    <!--begin::Row-->
    <div class="row mb-5">
        <div class="col-md-3">
            <div class="card card-flush mb-5 mb-xl-10 bg-info-subtle text-info-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-info-emphasis me-2 lh-1 ls-n2">{{ $newCustomersToday }}</span>
                        <span class="text-info-emphasis pt-1 fw-semibold fs-6">New Customers Today</span>
                    </div>
                    <x-lucide-user-plus class="tw-h-16 tw-w-16 text-info-emphasis"/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush mb-5 mb-xl-10 bg-primary-subtle text-primary-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-primary-emphasis me-2 lh-1 ls-n2">{{ $ordersToday }}</span>
                        <span class="text-primary-emphasis pt-1 fw-semibold fs-6">Orders Today</span>
                    </div>
                    <x-lucide-shopping-cart class="tw-h-16 tw-w-16 text-primary-emphasis"/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush mb-5 mb-xl-10 bg-warning-subtle text-warning-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-warning-emphasis me-2 lh-1 ls-n2">{{ $pendingOrders }}</span>
                        <span class="text-warning-emphasis pt-1 fw-semibold fs-6">Pending Orders</span>
                    </div>
                    <x-lucide-loader class="tw-h-16 tw-w-16 text-warning-emphasis"/>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle text-success-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span title="{{ number_format($totalCustomers) }}" class="fs-2hx fw-bold text-success-emphasis me-2 lh-1 ls-n2">{{ formatNumberToShort($totalCustomers) }}</span>
                        <span class="text-success-emphasis pt-1 fw-semibold fs-6">Total Customers</span>
                    </div>
                    <x-lucide-users class="tw-h-16 tw-w-16 text-success-emphasis"/>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->

    <!--begin::Card-->
    <div class="card border">
        <div class="card-body py-4">
            <h2>Recent Orders</h2>
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-2">
                    <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Order ID</th>
                        <th class="min-w-125px">Customer</th>
                        <th class="min-w-125px">Date</th>
                        <th class="min-w-125px">Total</th>
                        <th class="min-w-125px">Status</th>
                        <th class="text-end min-w-100px">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', encodeId($order->id)) }}" class="text-gray-800 text-hover-primary mb-1">
                                    #{{ $order->order_number ?? $order->id }}
                                </a>
                            </td>
                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                            <td>{{ $order->created_at->format('d M Y, h:i a') }}</td>
                            <td>{{ Number::currency($order->total_amount, 'RWF') }}</td>
                            <td>
                                <div class="badge badge-light-{{ $order->status_color }} rounded-pill">
                                    {{ ucwords($order->order_status) }}
                                </div>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', encodeId($order->id)) }}" class="btn btn-light btn-active-light-primary btn-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No recent orders found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
