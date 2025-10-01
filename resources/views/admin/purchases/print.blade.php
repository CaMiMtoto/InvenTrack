<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>
        {{$purchaseOrder->supplier?->name}} - {{$purchaseOrder->created_at->format('d/m/Y')}}
    </title>
    <style>
        @font-face {
            font-family: 'Figtree';
            src: url({{ storage_path('fonts/Figtree-Regular.ttf') }}) format("truetype");
            font-weight: 400;
            /*font-style: normal;*/
        }

        @page {
            margin: 0;
        }

        html, body {
            font-family: 'Figtree', sans-serif !important;
        }

    </style>
    <x-pdf-styles/>
</head>
<body class="tw-font-sans tw-antialiased">
<div class="-tw-top-1 tw-p-10">
    <table class="table">
        <tr>
            <td>
                <!--begin::Logo-->
                @php
                    $path = public_path('assets/media/logos/logo.png'); // full path to image
                    $type = pathinfo($path, PATHINFO_EXTENSION); // get file extension
                    $data = file_get_contents($path); // read file
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data); // encode
                @endphp
                <img src="{{ $base64 }}"
                     class="logo"  alt="Logo">
                <!--end::Logo-->
                <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                    {{ config('services.company.address_1') }}<br>
                    {{ config('services.company.address_2') }}
                </div>
            </td>
            <td class="text-end">
                <div>
                    <div class="tw-text-xs">Date:</div>
                    <strong class="pe-2 small">{{ now()->format('d M Y, H:i:s') }}</strong>
                </div>
            </td>
        </tr>

    </table>
    <hr/>
    <div class="text-center">
        <strong>Purchase Order</strong>
    </div>
    <hr/>
    <table class="table table-borderless">
        <tr>
            <td>
                <div class="tw-text-xs">Invoice #</div>
                <strong class="small">{{ $purchaseOrder->invoice_number }}</strong>
            </td>
            <td>
                <div>
                    <div class="tw-text-xs">Delivery Date:</div>
                    <strong class="small">{{ $purchaseOrder->purchased_at->format('d M Y') }}</strong>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="w-50">
                <div class="tw-text-justify">
                    <div class="tw-text-xs tw-text-gray-600 tw-mb-1">Issued By:</div>
                    <div class="tw-text-sm tw-text-gray-800">
                        <strong> {{ $purchaseOrder->supplier?->name??'Not Found' }}</strong>
                    </div>
                    <div class="tw-text-xs tw-text-gray-600 tw-mb-1">
                        {{ $purchaseOrder->supplier?->address??'Not Found' }}
                    </div>
                </div>
            </td>

        </tr>
    </table>
    <div>
        <div class="tw-text-xs tw-text-gray-700 tw-uppercase ">
            <strong>Items</strong>
        </div>
        <p class="tw-text-xs text-muted">
            Below are the items that you have purchased along with the total amount.
        </p>
    </div>
    <table class="table table-bordered border table-striped">
        <thead class="tw-text-xs tw-text-gray-700 tw-uppercase tw-bg-gray-50">
        <tr class=" tw-border-b tw-text-xs tw-font-bold tw-text-gray-700">
            <th class="tw-min-w-[175px] tw-p-2">Product</th>
            <th class="tw-min-w-[70px] tw-text-end tw-p-2">Price</th>
            <th class="tw-min-w-[80px] tw-text-end tw-p-2">Qty</th>
            <th class="tw-min-w-[100px] tw-text-end tw-p-2">Total</th>
        </tr>
        </thead>

        <tbody>
        @foreach($purchaseOrder->items as $item)
            <tr class="small tw-text-xs border-bottom">
                <td class=" tw-p-2">
                    {{ $item->product->name }}
                </td>

                <td class=" tw-p-2">
                    {{number_format($item->price, 0)}}
                </td>
                <td class=" tw-p-2">
                    {{ $item->quantity }}
                </td>
                <td class=" tw-p-2">
                    {{ number_format($item->total, 0) }}
                </td>
            </tr>
        @endforeach

        </tbody>
        <tfoot>
        <tr>
            <td colspan="3" class="tw-text-end tw-font-bold tw-text-gray-800 tw-p-2">Total</td>
            <td class="tw-text-end tw-font-bold tw-text-gray-800 tw-p-2">
                {{ number_format($purchaseOrder->total, 0) }}
            </td>
        </tr>
        </tfoot>
    </table>
    <div class="tw-flex tw-justify-end tw-mt-4">
        <div class="tw-text-xs tw-text-gray-600 tw-mb-1">Issued By:</div>
        <div class="tw-text-sm tw-text-gray-800">
            <strong> {{ $purchaseOrder->doneBy?->name }}</strong>
        </div>
    </div>
</div>
</body>
</html>
