<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Card Data
        $todaySales = Order::whereDate('created_at','>=', now()->subDays(7)->toDateString())
            ->where('order_status', '!=', 'cancelled')
            ->sum('total_amount');
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $stockAlerts = Product::whereColumn('stock', '<=', 'min_stock')
            ->count();
        $pendingDeliveries = Order::where('order_status', 'approved')
            ->count();
        $monthlyExpenses = Expense::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        // 2. Sales Performance Chart (Last 30 Days)
        $salesLast30Days = Order::query()
            ->whereDate('order_date', '>=', now()->subDays(30)->toDateString())
            ->whereNotIn('order_status', [Status::Pending, Status::Cancelled])
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('SUM(total_amount) as total_amount')
            )
            ->groupBy('order_date')
            ->orderBy('order_date', 'ASC')
            ->get();

        // Format for the chart
        $salesChartData = [];
        $salesChartLabels = [];
        // Create a period for the last 30 days to ensure all days are present
        $period = now()->subDays(29)->startOfDay()->toPeriod(now()->endOfDay());
        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $salesChartLabels[] = $date->format('M d');
            $sale = $salesLast30Days->firstWhere('date', $formattedDate);
            $salesChartData[] = $sale ? round($sale->total_amount, 2) : 0;
        }

        // 3. Order Status Breakdown
        $orderStatusCounts = Order::query()
            ->select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        $orderStatusChart = [
            'labels' => $orderStatusCounts->keys()->map('ucfirst')->all(),
            'data' => $orderStatusCounts->values()->all()
        ];

        // 4. Top Selling Products (This Month)
        $topSellingProducts = OrderItem::query()
            ->with('product')
            ->whereHas('order', function ($query) {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('order_status', '!=', 'cancelled');
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $topProductsChart = [
            'labels' => $topSellingProducts->map(fn($item) => $item->product->name ?? 'N/A')->all(),
            'data' => $topSellingProducts->pluck('total_quantity')->all(),
        ];

        // 5. Income vs Expenses (Last 6 Months)
        $incomeVsExpenses = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M');

            $income = Order::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->where('order_status', '!=', 'cancelled')
                ->sum('total_amount');

            $expense = Expense::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $incomeVsExpenses['labels'][] = $monthName;
            $incomeVsExpenses['income'][] = round($income, 2);
            $incomeVsExpenses['expenses'][] = round($expense, 2);
        }

        return view('admin.dashboard', compact(
            'todaySales',
            'pendingOrders',
            'stockAlerts',
            'pendingDeliveries',
            'monthlyExpenses',
            'salesChartLabels',
            'salesChartData',
            'orderStatusChart',
            'topProductsChart',
            'incomeVsExpenses'
        ));
    }
}
