<div class="mb-5">
    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-700 fw-bold">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 d-flex align-items-center justify-content-center">
                        <x-lucide-house class="tw-h-5 tw-w-5 text-gray-400"/>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <x-lucide-chevron-right class="tw-h-5 tw-w-5 text-gray-700 mx-n1"/>
                </li>
                <!--end::Item-->
                @foreach($breadcrumbs as $breadcrumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        @if(!$loop->last)
                            <a href="{{ $breadcrumb['url'] }}" class="text-gray-700 fw-bold">{{ $breadcrumb['label'] }}</a>
                            <x-lucide-chevron-right class="tw-h-5 tw-w-5 text-gray-700 mx-n1"/>
                        @else
                            {{ $breadcrumb['label'] }}
                        @endif
                    </li>
                @endforeach
            </ul>

            <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                {{ $title }}
            </h1>
        </div>

        <div class="d-flex align-items-center gap-2">
            @foreach($actions as $action)
                <a href="{{ $action['url'] }}" class="btn btn-sm {{ $action['class'] ?? 'btn-light-primary' }}">
                    {!! $action['icon'] ?? '' !!} {{ $action['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>
