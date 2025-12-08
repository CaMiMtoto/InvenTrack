<div class=" my-4">
    <div class="row g-3">

        <div class="col-md-3">
            <div class="card h-100 card-flush mb-5 bg-info-subtle tebg-info-emphasis">
                <div class="card-body">
                    <h6 class="card-title"># of Customers</h6>
                    <h1 class="tw-font-bold">{{ $totalCustomers }}</h1>
                    <x-lucide-users class="tw-w-10 tw-h-10"/>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 card-flush mb-5 bg-primary-subtle text-primary-emphasis">
                <div class="card-body">
                    <h6 class="card-title"># of Shareholders</h6>
                    <h1 class=" tw-font-bold">{{ $totalShareholders }}</h1>
                    <x-lucide-users class="tw-w-10 tw-h-10"/>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                <div class="card-body">
                    <h6 class="card-title">Approved Orders</h6>
                    <h1 class="tw-font-bold">{{ $approvedOrders }}</h1>
                    <x-lucide-check-check class="tw-w-10 tw-h-10"/>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                <div class="card-body">
                    <h6 class="card-title">Approved Shares</h6>
                    <h1 class="tw-font-bold">{{ $approvedShares }}</h1>
                    <x-lucide-check-square class="tw-w-10 tw-h-10"/>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                <div class="card-body">
                    <h6 class="card-title">Delivered Orders</h6>
                    <h1 class="tw-font-bold">{{ $deliveredOrders }}</h1>
                    <x-lucide-check-circle class="tw-w-10 tw-h-10"/>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 card-flush mb-5 bg-success-subtle text-success-emphasis">
                <div class="card-body">
                    <h6 class="card-title">Total Sales</h6>
                    <h1 class="tw-font-bold">{{ number_format($totalSales, 2) }} RWF</h1>
                    <x-lucide-shopping-cart class="tw-w-10 tw-h-10"/>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 card-flush mb-5 bg-danger-subtle text-danger-emphasis">
                <div class="card-body">
                    <h6 class="card-title">Total Expenses</h6>
                    <h1 class="tw-font-bold">{{ number_format($totalExpenses, 2) }} RWF</h1>
                    <x-lucide-trending-down class="tw-w-10 tw-h-10"/>
                </div>
            </div>
        </div>

    </div>
</div>
