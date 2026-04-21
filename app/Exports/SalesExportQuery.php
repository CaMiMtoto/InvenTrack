<?php

namespace App\Exports;

use App\Services\ReportService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class SalesExportQuery implements FromQuery, WithMapping, WithHeadings, WithChunkReading, ShouldAutoSize, WithTitle, WithStyles, WithEvents
{
    use Exportable;

    protected string $startDate;
    protected string $endDate;
    protected $productId;
    protected $user;
    protected $doneById;
    protected float $totalSales = 0;
    protected float $totalExpenses = 0;
    protected float $netProfit = 0;
    protected float $totalMargin = 0;

    public function __construct(string $startDate, string $endDate, $productId = null, $user = null, $doneById = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->productId = $productId;
        $this->user = $user;
        $this->doneById = $doneById;

        // Precompute totals efficiently using aggregate queries built from ReportService without the purchase_price subselect
        $service = new ReportService();
        $base = $service->getSalesQueryBuilder($this->startDate, $this->endDate, $this->productId, null, $this->doneById, false);

        // apply permission-based restriction same as UI
        $user = $this->user;
        $canSeeAll = $user && (
            $user->can(\App\Constants\Permission::VIEW_USER_SALES_PERFORMANCE) ||
            $user->can(\App\Constants\Permission::VIEW_FINANCIAL_DASHBOARD) ||
            $user->can(\App\Constants\Permission::VIEW_ADMIN_DASHBOARD)
        );
        if (!$canSeeAll && $user) {
            $base->whereHas('order', function(\Illuminate\Database\Eloquent\Builder $q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // Build a subquery that computes average purchase unit price per product and join it for efficient aggregation
        $avgSub = DB::table('purchase_items')->select('product_id', DB::raw('AVG(unit_price) as avg_price'))->groupBy('product_id');
        $builderWithAvg = (clone $base)
            ->leftJoinSub($avgSub, 'avg_prices', function($join) {
                $join->on('avg_prices.product_id', '=', 'order_items.product_id');
            })
            ->selectRaw('order_items.*, COALESCE(avg_prices.avg_price,0) as purchase_price');

        // total sales: sum(quantity * unit_price)
        $this->totalSales = (float) $builderWithAvg->sum(DB::raw('quantity * unit_price'));

        // total expenses from expenses query
        $this->totalExpenses = (float) $service->getExpensesQueryBuilder($this->startDate, $this->endDate)->sum(DB::raw('amount'));
        $this->netProfit = $this->totalSales - $this->totalExpenses;

        // total margin: aggregate with sum() to avoid selecting non-aggregated columns under ONLY_FULL_GROUP_BY
        $this->totalMargin = (float) (clone $builderWithAvg)
            ->sum(DB::raw('order_items.quantity * (order_items.unit_price - COALESCE(avg_prices.avg_price,0))'));
    }

    public function query()
    {
        $service = new ReportService();
        $base = $service->getSalesQueryBuilder($this->startDate, $this->endDate, $this->productId, null, $this->doneById, false);

        // apply permission-based restriction same as UI
        $user = $this->user;
        $canSeeAll = $user && (
            $user->can(\App\Constants\Permission::VIEW_USER_SALES_PERFORMANCE) ||
            $user->can(\App\Constants\Permission::VIEW_FINANCIAL_DASHBOARD) ||
            $user->can(\App\Constants\Permission::VIEW_ADMIN_DASHBOARD)
        );
        if (!$canSeeAll && $user) {
            $base->whereHas('order', function(\Illuminate\Database\Eloquent\Builder $q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // apply done-by filter (map doneById to created_by)
        if ($this->doneById) {
            $base->whereHas('order', function(\Illuminate\Database\Eloquent\Builder $q) {
                $q->where('created_by', $this->doneById);
            });
        }

        // join avg purchase prices and select purchase_price
        $avgSub = DB::table('purchase_items')->select('product_id', DB::raw('AVG(unit_price) as avg_price'))->groupBy('product_id');
        $builderWithAvg = (clone $base)
            ->leftJoinSub($avgSub, 'avg_prices', function($join) {
                $join->on('avg_prices.product_id', '=', 'order_items.product_id');
            })
            ->selectRaw('order_items.*, COALESCE(avg_prices.avg_price,0) as purchase_price');

        return $builderWithAvg;
    }

    public function chunkSize(): int
    {
        return 1000; // safe chunk size for FromQuery streaming
    }

    public function headings(): array
    {
        return [
            ['Sales Report'],
            [
                'Sales Order',
                'Date',
                'Item Name',
                'Price',
                'Quantity',
                'Total',
                'Margin',
                'Customer',
                'Done By',
            ]
        ];
    }

    public function map($row): array
    {
        $purchasePrice = $row->purchase_price ?? 0;
        $unitMargin = $row->unit_price - $purchasePrice;
        $marginTotal = $unitMargin * $row->quantity;

        return [
            $row->order->order_number,
            $row->order->order_date?->format('Y-m-d'),
            $row->product?->name,
            $row->unit_price,
            $row->quantity,
            $row->total,
            $marginTotal,
            $row->order->customer?->name,
            $row->order->doneBy?->name,
        ];
    }

    // chunkSize removed: export uses FromCollection with get(); consider switching back to FromQuery for very large exports

    public function title(): string
    {
        return 'Sales';
    }

    public function styles(Worksheet $sheet): array
    {
        // Merge title row and style headers
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2:I2')->getFont()->setBold(true);
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Append an empty row then totals
                $row = $highestRow + 2;

                $sheet->setCellValue("F{$row}", 'Total Sales:');
                $sheet->setCellValue("G{$row}", $this->totalSales);

                $row++;
                $sheet->setCellValue("F{$row}", 'Total Expenses:');
                $sheet->setCellValue("G{$row}", $this->totalExpenses);

                $row++;
                $sheet->setCellValue("F{$row}", 'Net Profit:');
                $sheet->setCellValue("G{$row}", $this->netProfit);

                $row++;
                $sheet->setCellValue("F{$row}", 'Total Margin:');
                $sheet->setCellValue("G{$row}", $this->totalMargin);

                // Format numbers as currency/number
                $sheet->getStyle("G" . ($highestRow + 2) . ":G{$row}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                // Bold labels
                $sheet->getStyle("F" . ($highestRow + 2) . ":F{$row}")->getFont()->setBold(true);
            }
        ];
    }
}

