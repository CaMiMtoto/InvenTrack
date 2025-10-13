<div class="card card-body bg-info-subtle text-info-emphasis">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw--semibold mb-2"> Inventory Status</h2>
            <p>Total Products: <span class="font-bold">{{ $totalProducts }}</span></p>
            <p class="text-danger-emphasis fw-bold">Low Stock: <span class="font-bold">{{ $lowStock }}</span></p>
        </div>
        <x-lucide-warehouse class="tw-h-16 tw-w-16"/>
    </div>
</div>
