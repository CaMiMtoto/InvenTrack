@props([
    'sortCol',
    'dir'
])

<th wire:click="handleSort('created_at')" {{ $attributes->class(['tw-cursor-pointer']) }}>
    <div class="w-100 d-flex justify-content-between align-items-center">
        <span>Created At</span>
        @if($sortCol == 'created_at' && $dir == 'asc')
            <x-lucide-chevron-up class="tw-w-5 tw-h-5 text-gray-500"/>
        @else
            <x-lucide-chevron-down class="tw-w-5 tw-h-5 text-gray-500"/>
        @endif
    </div>
</th>
