<div>
    <div class="card card-body bg-success-subtle text-success-emphasis">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw--semibold mb-2">Sales Overview</h2>
                <p>Total Sales: <span class="font-bold">{{ number_format($totalSales) }}</span></p>
                <p>Today: <span class="font-bold text-green-600">{{ number_format($todaySales) }}</span></p>
            </div>
            <span>
            <x-lucide-shopping-bag class="tw-h-16 tw-w-16"/>
        </span>
        </div>
    </div>
</div>
