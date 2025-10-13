<div class="">
    <p>
        Below are the statistics reported by the system.
    </p>

    <!--begin::Body-->
    <div class="">
        <div class="row g-3">
            {{-- Sales Overview --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-success-subtle text-success-emphasis">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-semibold mb-2">Sales Overview</h2>
                            <p>Total Sales: <span class="fw-bold">{{ number_format($totalSales) }} RWF</span></p>
                            <p>Today: <span class="fw-bold text-success">{{ number_format($todaySales) }} RWF</span></p>
                        </div>
                        <x-lucide-shopping-bag class="tw-h-16 tw-w-16"/>
                    </div>
                </div>
            </div>
            {{-- Orders --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-info-subtle text-info-emphasis">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-semibold mb-2">Orders</h2>
                            <p>Total Orders: <span class="fw-bold">{{ $totalOrders }}</span></p>
                            <p>Delivered: <span class="fw-bold text-info">{{ $deliveredOrders }}</span></p>
                        </div>
                        <x-lucide-package class="tw-h-16 tw-w-16"/>
                    </div>
                </div>
            </div>
            {{-- Returns --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger-subtle text-danger-emphasis">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-semibold mb-2">Returns</h2>
                            <p>Total Returns: <span class="fw-bold">{{ $totalReturns }}</span></p>
                            <p>This Week: <span class="fw-bold text-danger">{{ $weeklyReturns }}</span></p>
                        </div>
                        <x-lucide-rotate-ccw class="tw-h-16 tw-w-16"/>
                    </div>
                </div>
            </div>
            {{-- Products --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-warning-subtle text-warning-emphasis">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-semibold mb-2">Products</h2>
                            <p>Total: <span class="fw-bold">{{ $totalProducts }}</span></p>
                            <p>Low Stock: <span class="fw-bold text-warning">{{ $lowStockProducts }}</span></p>
                        </div>
                        <x-lucide-archive class="tw-h-16 tw-w-16"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4 g-3">
            <div class="col-md-6">
                <div class="card card-body border h-100 my-3">
                    <h5 class="fw-semibold mb-3">Sales (Last 7 Days)</h5>
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body border h-100 my-3">
                    <h5>Top Selling Products</h5>
                    <table class="table ps-2 align-middle  rounded table-row-dashed fs-6 gy-2" >
                        <thead>
                        <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                            <th>Product</th>
                            <th>Total Sold</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($topProducts as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->total_sold }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body border h-100 my-3">
                    <h5 class="fw-semibold mb-3">Return Reasons</h5>
                    <canvas id="returnsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!--end::Body-->

    @push('scripts')
        {{--        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>--}}
        <script>
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: @json($salesChartData['labels']),
                    datasets: [{
                        label: 'Sales (RWF)',
                        data: @json($salesChartData['data']),
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.2,
                        fill: false
                    }]
                }
            });

            new Chart(document.getElementById('returnsChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($returnsChartData['labels']),
                    datasets: [{
                        data: @json($returnsChartData['data']),
                        backgroundColor: ['#ef4444', '#3b82f6', '#f59e0b', '#10b981']
                    }]
                }
            });
        </script>
    @endpush
</div>
