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

        $this->prepareChartData();
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

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboards.customer-care-dashboard');
    }
}
