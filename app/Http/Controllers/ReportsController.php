<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExportQuery;

class ReportsController extends Controller
{

    public function salesReport()
    {
        $startDate = \request('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = \request('end_date', now()->format('Y-m-d'));
        $status = \request('status');

        return view('admin.reports.sales', [
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function printSales()
    {
        $startDate = request('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));
        $productId = request('product_id');

        $service = new \App\Services\ReportService();
        $builder = $service->getSalesQueryBuilder($startDate, $endDate, $productId);

        $user = auth()->user();
        $canSeeAll = $user && (
            $user->can(\App\Constants\Permission::VIEW_USER_SALES_PERFORMANCE) ||
            $user->can(\App\Constants\Permission::VIEW_FINANCIAL_DASHBOARD) ||
            $user->can(\App\Constants\Permission::VIEW_ADMIN_DASHBOARD)
        );

        if (!$canSeeAll && $user) {
            $builder->whereHas('order', function(\Illuminate\Database\Eloquent\Builder $q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        $sales = $builder->get();
        $totalSales = $sales->sum('total');
        $totalExpenses = $service->getExpensesQueryBuilder($startDate, $endDate)->sum(\DB::raw('amount'));
        $netProfit = $totalSales - $totalExpenses;

        $totalMargin = $sales->reduce(function($carry, $item) {
            $purchasePrice = $item->purchase_price ?? 0;
            $unitMargin = $item->unit_price - $purchasePrice;
            return $carry + ($unitMargin * $item->quantity);
        }, 0);

        // Prepare to generate PDF. Large reports can exhaust PHP memory with Dompdf.
        // Try to increase memory and execution time for the PDF generation step.
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        // Ensure dompdf temp directory exists and is writable
        $tempDir = storage_path('app/dompdf');
        if (!file_exists($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }

        // Configure Dompdf options to reduce resource usage where possible
        try {
            Pdf::setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'tempDir' => $tempDir,
                'dpi' => 96,
            ]);
        } catch (\Throwable $e) {
            // If setOptions is not available or fails, continue — ini_set/time limit will help.
        }

        // Use Dompdf to generate a PDF and stream it back to the browser
        $fileName = sprintf('sales-report-%s-to-%s.pdf', $startDate, $endDate);
        $pdf = Pdf::loadView('admin.reports.print-sales', compact('sales','totalSales','totalExpenses','netProfit','totalMargin','startDate','endDate'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream($fileName);
    }

    public function exportSales()
    {
        $startDate = request('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));
        $productId = request('product_id');

        $user = auth()->user();
        $fileName = sprintf('sales-export-%s-to-%s.xlsx', $startDate, $endDate);

        $export = new SalesExportQuery($startDate, $endDate, $productId, $user);
        return Excel::download($export, $fileName);
    }



    public function exportPurchases()
    {

    }

    public function stockReport()
    {

    }

    public function paymentsReport()
    {

    }

    public function printPayments()
    {

    }

    public function itemsReport()
    {

    }

    public function expensesReport()
    {

    }
}
