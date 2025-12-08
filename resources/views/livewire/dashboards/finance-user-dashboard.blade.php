<div class=" my-4">

    <div class="row mb-4">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    {{-- Total Sales --}}
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                            <div class="card-body">
                                <h1 class="card-title">Total Sales</h1>
                                <h3 class="tw-text-xl tw-font-bold">
                                    {{ number_format($totalSales) }} RWF
                                </h3>
                                <x-lucide-shopping-cart class="tw-w-10 tw-h-10"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                            <div class="card-body">
                                <h1 class="card-title">Goods Sold</h1>
                                <h3 class="tw-text-xl tw-font-bold">
                                    {{ number_format($totalGoodsSales) }} RWF
                                </h3>
                                <x-lucide-shopping-bag class="tw-w-10 tw-h-10"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                            <div class="card-body">
                                <h1 class="card-title">Shares Sold</h1>
                                <h3 class="tw-text-xl tw-font-bold">
                                    {{ number_format($totalShareSales) }} RWF
                                </h3>
                                <x-lucide-shell class="tw-w-10 tw-h-10"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 card-flush mb-5 bg-danger-subtle text-danger-emphasis">
                            <div class="card-body">
                                <h1 class="card-title">Total Expenses</h1>
                                <h3 class="tw-text-xl tw-font-bold">
                                    {{ number_format($totalExpenses) }} RWF
                                </h3>
                                <x-lucide-circle-arrow-out-down-right class="tw-w-10 tw-h-10"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                            <div class="card-body">
                                <h1 class="card-title">Total Purchases</h1>
                                <h3 class="tw-text-xl tw-font-bold">
                                    {{ number_format($totalPurchases) }} RWF
                                </h3>
                                <x-lucide-wallet-cards class="tw-w-10 tw-h-10"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 card-flush mb-5">
                    <div class="card-body">
                        <h6 class="card-title">Top Staff Performance</h6>
                        <table class="table table-sm  mt-2">
                            <thead>
                            <tr>
                                <th>Staff</th>
                                <th>Total Sales</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($staffPerformance as $staff)
                                <tr>
                                    <td>{{ $staff['name'] }}</td>
                                    <td>{{ number_format($staff['total_sales'] ?? 0) }} RWF</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>
