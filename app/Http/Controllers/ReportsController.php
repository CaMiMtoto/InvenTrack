<?php

namespace App\Http\Controllers;

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

    }

    public function exportSales()
    {

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
