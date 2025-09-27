<?php

namespace App\Exports;

use App\Services\TransactionService;
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

class WalletTransactionsExportQuery implements
    WithMapping,
    WithHeadings,
    WithTitle,
    ShouldAutoSize,
    WithStyles,
    WithEvents,
    FromQuery,
    WithChunkReading
{
    protected array $filters;
    protected TransactionService $service;

    public function __construct(array $filters, TransactionService $service)
    {
        $this->filters = $filters;
        $this->service = $service;
    }


    public function headings(): array
    {
        return [
            ['T Pay Wallet Transactions Report'], // Title row
            [
                'Date',
                'Merchant',
                'Wallet Type',
                'Description',
                'Amount',
                'Balance After',
                'Type',
            ]
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->toDateTimeString(),
            $row->wallet?->merchant?->name,
            ucfirst($row->wallet->type),
            $row->description,
            number_format($row->amount),
            number_format($row->balance_after),
            ucfirst($row->type),
        ];
    }

    public function title(): string
    {
        return 'Wallet Transactions';
    }

    /**
     * @throws Exception
     */
    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);

        // Align amount to left
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle("E3:E{$highestRow}")
            ->getAlignment()
            ->setHorizontal('left');

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $row = 3;

                while (true) {
                    $type = $sheet->getCell("G{$row}")->getValue(); // G = Type

                    if (!$type) break;

                    if (strtolower($type) === 'credit') {
                        $sheet->getStyle("G{$row}")
                            ->getFont()
                            ->getColor()
                            ->setRGB('008000'); // green
                    } elseif (strtolower($type) === 'debit') {
                        $sheet->getStyle("G{$row}")
                            ->getFont()
                            ->getColor()
                            ->setRGB('FF0000'); // red
                    }

                    $row++;
                }
            }
        ];
    }

    public function query()
    {
        return $this->service->getWalletTransactions($this->filters);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
