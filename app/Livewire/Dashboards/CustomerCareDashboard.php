<?php

namespace App\Livewire\Dashboards;

use App\Constants\Status;
use App\Models\Customer;
use App\Models\CustomerShare;
use App\Models\Order;
use App\Models\Share;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CustomerCareDashboard extends Component
{
    public int $newCustomersToday = 0;
    public int $ordersToday = 0;
    public int $pendingOrders = 0;
    public int $totalCustomers = 0;
    public $recentOrders;
    public int $myPaidOrders = 0;
    public int $myApprovedShares = 0;
    public array $myPaidOrdersChartData = [];
    public array $myApprovedSharesChartData = [];
    public $startDate;
    public $endDate;
    public $performance;
    public function mount(): void
    {
        $today = Carbon::today();
        $userId = auth()->id();

        $this->newCustomersToday = Customer::query()
            ->whereDate('created_at', $today)
            ->count();

        $this->ordersToday = Order::query()
            ->whereDate('created_at', $today)
            ->count();

        $this->pendingOrders = Order::query()
            ->whereNotIn('order_status', ['delivered', 'completed', 'cancelled', 'returned'])
            ->count();

        $this->totalCustomers = Customer::query()->count();

        $this->recentOrders = Order::query()->with('customer')->latest()->take(10)->get();

        $this->myPaidOrders = Order::query()
            ->where('created_by', $userId)
            ->where('order_status', '=', Status::Paid)
            ->count();

        $this->myApprovedShares = Share::query()
            ->where('user_id', '=', $userId)
            ->where('status', '=', Status::Approved)
            ->count();
//
//        $this->prepareChartData();
        $this->loadPerformance();
    }

    private function prepareChartData(): void
    {
        $userId = auth()->id();
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(6);

        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->put($date->format('Y-m-d'), 0);
        }

        $paidOrdersData = Order::query()
            ->where('created_by', $userId)
            ->where('order_status', '=',Status::Paid)
            ->whereBetween('created_at', [$startDate, $endDate->copy()->endOfDay()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        $this->myPaidOrdersChartData = $dates->merge($paidOrdersData)->all();

        $approvedSharesData = Share::query()
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate->copy()->endOfDay()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->pluck('count', 'date');

        $this->myApprovedSharesChartData = $dates->merge($approvedSharesData)->all();
    }
    public function loadPerformance(): void
    {
        $userId = auth()->id();

        $query = DB::table('users')
            ->select(
                DB::raw('ROUND(SUM(order_items.quantity * order_items.unit_price * COALESCE(product_classes.rate, 0) / 100), 0) as performance_score'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_items')
            )
            ->join('orders', 'orders.created_by', '=', 'users.id')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('product_classes', 'product_classes.id', '=', 'products.product_class_id')
            ->where('users.id', $userId);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('orders.order_date', [$this->startDate, $this->endDate]);
        }

        $this->performance = $query->first();
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {

        return view('livewire.dashboards.customer-care-dashboard');
    }
}
