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
                        Wallet Transactions
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item">
                        <x-lucide-chevron-right class="text-gray-400 mx-n1 tw-h-5 tw-w-5"/>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-700">
                        Wallet Transactions
                    </li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
                <!--begin::Title-->
                <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bolder fs-1 lh-0">
                    Wallet Transactions
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
                    <button wire:click.prevent="exportWalletTransactions"
                            wire:loading.attr="disabled"
                            class="btn btn-light-success btn-sm ">
                            <span wire:loading.remove wire:target="exportWalletTransactions">
                                <x-lucide-download class="me-1 tw-h-5 tw-w-5"/> Export to Excel
                            </span>
                        <span wire:loading wire:target="exportWalletTransactions">
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
                    <th>Merchant</th>
                    <x-table.sortable label="Amount" column="amount" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('amount')"/>
                    <x-table.sortable label="Description" column="description" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('description')"/>
                    <x-table.sortable label="Balance" column="balance_after" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('balance_after')"/>
                    <x-table.sortable label="Type" column="type" :sortCol="$sortCol" :dir="$dir"
                                      wireClick="handleSort('type')"/>
                    <th>TXN ID</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transactions as $item)
                    <tr>
                        <td>
                            <span>
                                @if($item->type==\App\Models\WalletTransaction::DEBIT)
                                    <x-lucide-trending-down class="tw-w-4 me-2 tw-h-4 text-danger"/>
                                @elseif($item->type==\App\Models\WalletTransaction::CREDIT)
                                    <x-lucide-trending-up class="tw-w-4 me-2 tw-h-4 text-success"/>
                                @endif
                            </span>

                            {{ $item->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td>{{ $item->wallet->merchant->name }}</td>
                        <td>{{ number_format($item->amount) }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ number_format($item->balance_after) }}</td>
                        <td>
                            <span
                                class="badge rounded-pill bg-light-{{ $item->type == \App\Models\WalletTransaction::DEBIT ? 'danger' : 'success' }} text-{{ $item->type == \App\Models\WalletTransaction::DEBIT ? 'danger' :'success' }}-emphasis fw-bold">
                                {{ ucfirst($item->type) }}
                            </span>
                        </td>
                        <td>{{ $item->txn_id }}</td>
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
            {{ $transactions->links() }}
        </div>
    </div>


</div>
