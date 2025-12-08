<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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



    /** TOTAL SALES: Goods + Shares */
    public function getTotalSales()
    {
        // Total sales from GOODS
        $totalGoods = Order::whereIn('order_status', ['paid', 'completed'])
            ->sum('total_amount');

        // Total sales from SHARES
        $totalShares = DB::table('shares')
            ->where('status', 'approved')
            ->sum(DB::raw('value * quantity'));

        return $totalGoods + $totalShares;
    }

    /** TOTAL PURCHASES from purchase_items */
    public function getTotalPurchases()
    {
        return DB::table('purchase_items')
            ->sum(DB::raw('quantity * unit_price'));
    }

    /** TOTAL EXPENSES */
    public function getTotalExpenses()
    {
        return DB::table('expenses')->sum('amount');
    }

    /** STAFF PERFORMANCE */
    public function getStaffPerformance()
    {
        return User::select(
            'users.id as user_id',
            'users.name as user_name',
            DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
            DB::raw('SUM(order_items.quantity) as total_items'),
            DB::raw('ROUND(SUM(order_items.quantity * order_items.unit_price * (product_classes.rate / 100)), 0) as performance_score')
        )
            ->join('orders', 'orders.created_by', '=', 'users.id')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('product_classes', 'product_classes.id', '=', 'products.product_class_id')
            ->whereIn('orders.order_status', ['paid', 'completed'])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('performance_score')
            ->get();
    }

}

