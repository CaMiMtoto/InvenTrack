<th wire:click="{{ $wireClick }}" class="tw-cursor-pointer">
    <div class="w-100 d-flex justify-content-between align-items-center">
        <span>{{ $label }}</span>

        @if($sortCol === $column)
            @if($dir === 'asc')
                <x-lucide-chevron-up class="tw-w-5 tw-h-5 text-gray-500"/>
            @else
                <x-lucide-chevron-down class="tw-w-5 tw-h-5 text-gray-500"/>
            @endif
{{--        @else--}}
{{--            <x-lucide-chevron-down class="tw-w-5 tw-h-5 text-gray-300 opacity-50"/>--}}
        @endif
    </div>
</th>
