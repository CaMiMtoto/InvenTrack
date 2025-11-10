<?php

namespace App\Livewire\Dashboards;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\User;

class SalesManagerDashboard extends Component
{
    public $totalSales;
    public $salesGrowth;
    public $newCustomers;

    public function mount(): void
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $this->totalSales = Order::query()
            ->where('order_status', '!=', 'cancelled')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');

        $previousMonth = Carbon::now()->subMonth()->month;
        $previousYear = Carbon::now()->subMonth()->year;

        $previousMonthSales = Order::query()
            ->where('order_status', '!=', 'cancelled')
            ->whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->sum('total_amount');

        $this->salesGrowth = ($previousMonthSales > 0) ? (($this->totalSales - $previousMonthSales) / $previousMonthSales) * 100 : 0;

        $this->newCustomers = User::query()
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboards.sales-manager-dashboard');
    }
}
