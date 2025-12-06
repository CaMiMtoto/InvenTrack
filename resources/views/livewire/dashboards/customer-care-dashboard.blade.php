<div class="mt-5">
    <!--begin::Row-->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card card-flush mb-5 mb-xl-10 bg-info-subtle">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <div class="fs-2hx fw-bold text-info-emphasis me-2 lh-1 ls-n2">{{ $newCustomersToday }}</div>
                        <span class="text-info-emphasis pt-1 fw-semibold fs-6">New Customers Today</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                    <div class="d-flex flex-column content-justify-center flex-row-fluid">
                        <div class="d-flex fw-semibold align-items-center">
                            <h4 class="text-info-emphasis flex-grow-1 me-4">Total Customers</h4>
                            <h4 class="fw-bolder text-info-emphasis text-xxl-end">{{ number_format($totalCustomers) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush mb-5 mb-xl-10 bg-primary-subtle">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-primary-emphasis me-2 lh-1 ls-n2">{{ $ordersToday }}</span>
                        <span class="text-primary-emphasis pt-1 fw-semibold fs-6">Orders Today</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                    <div class="d-flex flex-column content-justify-center flex-row-fluid">
                        <div class="d-flex fw-semibold align-items-center">
                            <h4 class="text-primary-emphasis flex-grow-1 me-4">Pending Orders</h4>
                            <h4 class="fw-bolder text-primary-emphasis text-xxl-end">{{ number_format($pendingOrders) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-flush mb-5 mb-xl-10 bg-success-subtle">
                <div class="card-header pt-5">
                    <div class="card-title d-flex flex-column">
                        <span class="fs-2hx fw-bold text-success-emphasis me-2 lh-1 ls-n2">{{ $myPaidOrders }}</span>
                        <span class="text-success-emphasis pt-1 fw-semibold fs-6">My Paid Orders</span>
                    </div>
                </div>
                <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                    <div class="d-flex flex-column content-justify-center flex-row-fluid">
                        <div class="d-flex fw-semibold align-items-center">
                            <h4 class="text-success-emphasis flex-grow-1 me-4">My Approved Shares</h4>
                            <h4 class="fw-bolder text-success-emphasis text-xxl-end">{{ number_format($myApprovedShares) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->

{{--    <div class="row g-5 g-xl-8">
        <div class="col-xl-6">
            <!--begin::Chart Widget 1-->
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">My Paid Orders (Last 7 Days)</span>
                    </h3>
                </div>
                <div class="card-body" wire:ignore>
                    <canvas id="myPaidOrdersChart"></canvas>
                </div>
            </div>
            <!--end::Chart Widget 1-->
        </div>
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <div class="card-header">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">My Approved Shares (Last 7 Days)</span>
                    </h3>
                </div>
                <div class="card-body" wire:ignore>
                    <canvas id="myApprovedSharesChart"></canvas>
                </div>
            </div>
        </div>
    </div>--}}
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                const paidOrdersData = @json($myPaidOrdersChartData);
                const approvedSharesData = @json($myApprovedSharesChartData);

                const paidOrdersCtx = document.getElementById('myPaidOrdersChart').getContext('2d');
                new Chart(paidOrdersCtx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(paidOrdersData).map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                        datasets: [{
                            label: 'Paid Orders',
                            data: Object.values(paidOrdersData),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                const approvedSharesCtx = document.getElementById('myApprovedSharesChart').getContext('2d');
                const approvedSharesChart = new Chart(approvedSharesCtx, {
                    type: 'line',
                    data: {
                        labels: Object.keys(approvedSharesData).map(date => new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                        datasets: [{
                            label: 'Approved Shares',
                            data: Object.values(approvedSharesData),
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                    }
                });
            });
        </script>
    @endpush
</div>
