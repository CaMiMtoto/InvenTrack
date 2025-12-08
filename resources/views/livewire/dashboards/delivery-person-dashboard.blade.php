<div>
    <!--begin::Row-->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-md-4">
            <div class="card card-flush mb-5 mb-xl-10 bg-primary-subtle text-primary-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-primary-emphasis me-2 lh-1 ls-n2">{{ $totalAssigned }}</span>
                        <span class="text-primary-emphasis pt-1 fw-semibold fs-4">Assigned Deliveries</span>
                    </div>
                    <x-lucide-map class="tw-h-8 tw-w-8 text-primary-emphasis"/>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle text-success-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-success-emphasis me-2 lh-1 ls-n2">{{ number_format($delivered) }}</span>
                        <span class="text-success-emphasis pt-1 fw-semibold fs-6">Delivered</span>
                    </div>
                    <x-lucide-package-check class="tw-h-8 tw-w-8 text-success-emphasis"/>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="card card-flush mb-5 mb-xl-10 bg-warning-subtle text-warning-emphasis">
                <div class="card-body">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-warning-emphasis me-2 lh-1 ls-n2">{{ number_format($partiallyDelivered) }}</span>
                        <span class="text-warning-emphasis pt-1 fw-semibold fs-6">
                            Partially Delivered
                        </span>
                    </div>
                    <x-lucide-package class="tw-h-8 tw-w-8 text-warning-emphasis"/>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->

    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <h2>Recent Deliveries</h2>
            </div>
            <!--end::Card title-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-2">
                    <thead>
                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Date</th>
                        <th class="min-w-125px">Order #</th>
                        <th class="min-w-125px">Items</th>
                        <th class="min-w-125px">Customer</th>
                        <th class="min-w-125px">Address</th>
                        <th class="min-w-125px">Status</th>
{{--                        <th class="text-end min-w-100px">Actions</th>--}}
                    </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                    @forelse($todaysDeliveries as $order)
                        <tr>
                            <td>{{ $order->order->created_at->format('d M Y,H:i ') }}</td>
                            <td>{{ $order->order->order_number }}</td>
                            <td>{{ number_format($order->items_count) }}</td>
                            <td>{{ $order->order->customer->name ?? 'N/A' }}</td>
                            <td>{{ $order->order->customer->address }}</td>
                            <td>
                                <div class="badge badge-light-{{ $order->statusColor }} rounded-pill">
                                    {{ str_replace('_', ' ', Str::title($order->status)) }}
                                </div>
                            </td>
                       {{--     <td class="text-end">
                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm">
                                    View
                                </a>
                            </td>--}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No deliveries assigned for today.</td>
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
