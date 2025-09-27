<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Service;
use App\Models\Settlement;
use App\Models\Transaction;
use App\Models\Wallet;
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
}

