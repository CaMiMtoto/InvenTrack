<?php

namespace App\Exports;

use App\Services\TransactionService;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ElectricityTokenExportQuery implements FromQuery, WithMapping, WithHeadings, WithChunkReading, ShouldAutoSize, WithTitle, WithStyles, WithEvents
{
    use Exportable;

    protected array $filters;
    private TransactionService $transactionService;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->transactionService = new TransactionService();
    }

    public function query()
    {
        return $this->transactionService->getElectricityTokens($this->filters);
    }

    public function headings(): array
    {
        return [
            ['Electricity Tokens Report'], // ✅ Excel title (first row)
            [
                'Date',
                'Token',
                'Units',
                'Amount Paid',
                'Customer',
                'Receipt No',
                'Invoice Number',
                'Merchant',
                'Transaction Id',
            ]];
    }

    public function map($row): array
    {
        return [
            $row->created_at->toDateTimeString(),
            $row->token,
            $row->units,
            number_format($row->transaction->amount),
            $row->transaction->customer_name,
            $row->receipt_no,
            $row->invoice_no,
            $row->transaction->merchant->name,
            $row->transaction->txn_id,
        ];
    }

    public function chunkSize(): int
    {
        return 1000; // safe for most servers
    }

    public function title(): string
    {
        return 'Electricity Tokens'; // Sheet tab name
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): array
    {
        // Merge and center the title
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')
            ->getAlignment()->setHorizontal('center');

        // Bold title and header
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2:I2')->getFont()->setBold(true);

        // Align "Amount" column  to the left (data rows only)
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("D3:F{$highestRow}")
            ->getAlignment()
            ->setHorizontal('left');

        return [];
    }


    public function registerEvents(): array
    {
        return [

        ];
    }
}
