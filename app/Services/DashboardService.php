<?php

namespace App\Services;

use App\Constants\Status;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function getSummary($user, $from = null, $to = null): array
    {
        $from = $from ? Carbon::parse($from)->startOfDay() : now()->startOfMonth();
        $to = $to ? Carbon::parse($to)->endOfDay() : now()->endOfDay();
        $from = $from->toDateString();
        $to = $to->toDateString();



        return [
            'from' => $from,
            'to' => $to,
            'utilities' => [
                'airtime_sold' =>9000,
                'cash_power_sold' => 50000,
                'water_payments' => 30000,
                'utility_balance' => 7900000, // or total sum across all if admin
            ],
            'payments' => [
                'payments_received' => 800,
                'payments_disbursed' => 400,
                'settlements' => 200,
                'payment_balance' => 10000, // or total sum across all if admin
            ]
        ];
    }

    public function getDailyChartData($user, $from = null, $to = null): array
    {

        return [
            'labels' => ['2024-06-01', '2024-06-02', '2024-06-03', '2024-06-04', '2024-06-05', '2024-06-06', '2024-06-07'],
            'airtime' => [1000, 1500, 2000, 2500, 3000, 3500, 4000],
            'water' => [2000, 2500, 3000, 3500, 4000, 4500, 5000],
            'electricity' => [3000, 3500, 4000, 4500, 5000, 5500, 6000]
        ];
    }

    public function getPaymentFlowChartData($user, $from = null, $to = null): array
    {


        return [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'received' => [500, 600, 700, 800, 900, 1000, 1100],
            'disbursed' => [300, 400, 500, 600, 700, 800, 900]
        ];
    }

    public function getTopMerchants(): array
    {
        return [
            ['name' => 'Merchant A', 'total_transactions' => 150, 'total_amount' => 75000],
            ['name' => 'Merchant B', 'total_transactions' => 120, 'total_amount' => 60000],
            ['name' => 'Merchant C', 'total_transactions' => 100, 'total_amount' => 50000],
        ];
    }
}

