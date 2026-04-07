<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Report</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px }
        table { width: 100%; border-collapse: collapse }
        th, td { border: 1px solid #ddd; padding: 6px }
        th { background: #f5f5f5 }
        .text-right { text-align: right }
        .text-center { text-align: center }
    </style>
</head>
<body>
    <h2>Sales Report</h2>
    <p>From: {{ $startDate }} To: {{ $endDate }}</p>

    <table>
        <thead>
        <tr>
            <th>Sales Order</th>
            <th>Date</th>
            <th>Item Name</th>
            <th class="text-right">Price</th>
            <th class="text-right">Quantity</th>
            <th class="text-right">Total</th>
            <th class="text-right">Margin</th>
            <th>Customer</th>
            <th>Done By</th>
        </tr>
        </thead>
        <tbody>
        @forelse($sales as $item)
            @php
                $purchasePrice = $item->purchase_price ?? 0;
                $unitMargin = $item->unit_price - $purchasePrice;
                $marginTotal = $unitMargin * $item->quantity;
            @endphp
            <tr>
                <td>{{ $item->order->order_number }}</td>
                <td>{{ $item->order->order_date->format('d-m-Y') }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">{{ number_format($item->unit_price,2) }}</td>
                <td class="text-right">{{ number_format($item->quantity,2) }}</td>
                <td class="text-right">{{ number_format($item->total,2) }}</td>
                <td class="text-right">{{ number_format($marginTotal,2) }}</td>
                <td>{{ optional($item->order->customer)->name }}</td>
                <td>{{ optional($item->order->doneBy)->name }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No sales found for the selected range.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px; display:flex; justify-content:flex-end; gap:18px">
        <div>
            <strong>Total Sales:</strong> {{ number_format($totalSales,2) }}
        </div>
        <div>
            <strong>Total Expenses:</strong> {{ number_format($totalExpenses,2) }}
        </div>
        <div>
            <strong>Net Profit:</strong> {{ number_format($netProfit,2) }}
        </div>
        <div>
            <strong>Total Margin:</strong> {{ number_format($totalMargin,2) }}
        </div>
    </div>
</body>
</html>

