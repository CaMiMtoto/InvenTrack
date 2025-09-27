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

class TransactionsExportQuery implements FromQuery, WithMapping, WithHeadings, WithChunkReading, ShouldAutoSize, WithTitle, WithStyles, WithEvents
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
        return $this->transactionService->getTransactionsBuilder($this->filters, 'created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            ['T Pay Transaction Report'], // ✅ Excel title (first row)
            [
                'Date',
                'Merchant',
                'Service',
                'Reference',
                'Customer',
                'Amount',
                'Status',
            ]];
    }

    public function map($row): array
    {
        return [
            $row->created_at->toDateTimeString(),
            $row->merchant?->name,
            $row->service?->name,
            $row->account_reference,
            $row->customer_name,
            number_format($row->amount),
            $row->status,
        ];
    }

    public function chunkSize(): int
    {
        return 1000; // safe for most servers
    }

    public function title(): string
    {
        return 'Transactions'; // Sheet tab name
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): array
    {
        // Merge and center the title
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Bold title and header
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);

        // Align "Amount" column (Column F) to the left (data rows only)
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("F3:F{$highestRow}")
            ->getAlignment()
            ->setHorizontal('left');

        return [];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $row = 3; // Start from data row (after title and header)
                while (true) {
                    $status = $sheet->getCell("G{$row}")->getValue(); // Column G = Status

                    if ($status === null) {
                        break; // Exit when data ends
                    }

                    if (strtolower($status) === 'success') {
                        $sheet->getStyle("G{$row}")
                            ->getFont()
                            ->getColor()
                            ->setRGB('008000'); // green
                    } elseif (strtolower($status) === 'failed') {
                        $sheet->getStyle("G{$row}")
                            ->getFont()
                            ->getColor()
                            ->setRGB('FF0000'); // red
                    }

                    $row++;
                }
            },
        ];
    }
}
