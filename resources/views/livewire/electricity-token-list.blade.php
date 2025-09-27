<div>
    <!--begin::Toolbar-->
    <div class="mb-5">
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column gap-1 me-3 mb-2">
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-6">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-500">
                            <x-lucide-house class="fs-3 text-gray-400 me-n1 tw-h-5 tw-w-5"/>
                        </a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                        Transactions
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <x-lucide-chevron-right class="text-gray-400 mx-n1 tw-h-5 tw-w-5"/>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700">
                        Manage Transactions
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    Transactions
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            <div class="d-flex flex-column gap-2 align-items-start flex-md-row">
                <div>
                    <label for="dateRange" class="visually-hidden">Date Range</label>
                    <select class="form-select form-select-sm" id="dateRange" wire:model.live="dateRange"
                            wire:change="handleDateChange">
                        <option value="all">All</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_week">This Week</option>
                        <option value="last_week">Last Week</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_year">This Year</option>
                        <option value="last_year">Last Year</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div>
                    <button wire:click.prevent="exportToExcel"
                            wire:loading.attr="disabled"
                            class="btn btn-light-success btn-sm ">
                            <span wire:loading.remove wire:target="exportToExcel">
                                <x-lucide-download class="me-1 tw-h-5 tw-w-5"/> Export to Excel
                            </span>
                        <span wire:loading wire:target="exportToExcel">
                            <x-lucide-loader class="me-1 tw-h-5 tw-w-5 tw-animate-spin"/> Preparing...
                        </span>
                    </button>
                </div>
            </div>

            <!--end::Actions-->
        </div>
    </div>
    <!--end::Toolbar-->

    <div class="my-3">

        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                <div>
                    <label for="perPage" class="visually-hidden">Show</label>
                    <select class="form-select form-select-sm" id="perPage" wire:model.live="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                    @if(is_null(auth()->user()->merchant_id))
                        <select class="form-select form-select-sm" wire:model.live="merchantId">
                            <option value="">All Merchants</option>
                            @foreach($merchants as $merchant)
                                <option value="{{ $merchant->id }}">{{ $merchant->name }}</option>
                            @endforeach
                        </select>
                    @endif

                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                @if($dateRange == 'custom')
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
                        <input type="date" wire:model.live="startDate"
                               class="form-control form-control-sm min-w-lg-250px"/>
                        <input type="date" wire:model.live="endDate"
                               class="form-control form-control-sm min-w-lg-250px"/>
                    </div>
                @endif

                <div>
                    <label for="search" class="visually-hidden">Search</label>
                    <input type="text" placeholder="Search ... " class="form-control form-control-sm min-w-lg-250px"
                           id="search" wire:model.live.debounce="search"/>
                </div>
            </div>
        </div>
        <div class="table-responsive position-relative">
            <div
                class="position-absolute top-50 start-50 z-3 translate-middle  border border-gray-300 rounded-3 p-3 opacity-100 shadow"
                wire:loading>
                <x-lucide-loader class="tw-animate-spin tw-h-5 tw-w-5"/>
                <span>Please wait...</span>
            </div>
            <table
                class="table ps-2 align-middle position-relative table-row-bordered table-row-gray-200 align-middle  fs-6 gy-4"
                wire:loading.class="opacity-25">
                <thead>
                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase">
                    <x-table.sortable label="Created At" column="created_at" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('created_at')"/>
                    <x-table.sortable label="Tokens" column="token" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('token')"/>
                    <x-table.sortable label="Units" column="units" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('units')"/>
                    <th>Amount</th>
                    <th>Customer Name</th>
                    <x-table.sortable label="Receipt No" column="receipt_no" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('receipt_no')"/>
                    <x-table.sortable label="Invoice No" column="invoice_no" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('invoice_no')"/>
                    <th>Merchant</th>
                </tr>
                </thead>
                <tbody>
                @forelse($tokens as $item)
                    <tr>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <code class="fw-bold">{{ formatToken($item->token) }}</code>
                            <x-lucide-copy
                                class="text-gray-400 cursor-pointer tw-w-4 tw-h-4 copy-token-icon"
                                data-token="{{ $item->token }}"
                                title="Copy token"
                            />
                            <span class="copied-tooltip tw-hidden tw-text-green-600 tw-text-xs tw-font-bold tw-ml-2">Copied!</span>
                        </td>

                        <td>{{$item->units}}</td>
                        <td>{{ number_format($item->transaction->amount) }}</td>
                        <td>{{ $item->transaction->customer_name }}</td>
                        <td>{{ $item->receipt_no }}</td>
                        <td>{{ $item->invoice_no }}</td>
                        <td>{{ $item->transaction->merchant->name }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            No transactions found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            {{ $tokens->links() }}
        </div>
    </div>


</div>
@script
<script>

    const copyIcons = document.querySelectorAll('.copy-token-icon');
    copyIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const token = this.dataset.token;
            if (!token) return;

            // Create temporary textarea to copy
            const el = document.createElement('textarea');
            el.value = token;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);

            // Show "Copied!" tooltip
            const tooltip = this.nextElementSibling;
            tooltip.classList.remove('tw-hidden');

            setTimeout(() => {
                tooltip.classList.add('hidden');
            }, 2000); // hide after 2 seconds
        });
    });
</script>
@endscript
