<div class="card card-body bg-primary-subtle  text-primary-emphasis">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-lg font-semibold mb-2">Customer Stats</h2>
            <p>Total Customers: <span class="font-bold">{{ $totalCustomers }}</span></p>
            <p>Active (last 30 days): <span class="font-bold text-blue-600">{{ $activeCustomers }}</span></p>
        </div>
        <x-lucide-users class="tw-h-16 tw-w-16"/>
    </div>
</div>
