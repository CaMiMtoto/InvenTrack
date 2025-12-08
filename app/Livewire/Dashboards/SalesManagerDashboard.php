<?php

namespace App\Livewire\Dashboards;

use App\Constants\Status;
use App\Models\Order;
use App\Models\Share;
use Illuminate\Support\Carbon;
use Livewire\Component;
use App\Models\User;

class SalesManagerDashboard extends Component
{
    public $assignedOrders;
    public $returnedOrders;
    public $deliveredOrders;
    public $partiallyDeliveredOrders;
    public $approvedShares;

    public function mount(): void
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $this->assignedOrders = Order::query()
            ->where('order_status', '=', Status::PendingDelivery)->count();

        $previousMonth = Carbon::now()->subMonth()->month;
        $previousYear = Carbon::now()->subMonth()->year;

        $previousMonthSales = Order::query()
            ->where('order_status', '!=', 'cancelled')
            ->whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousYear)
            ->sum('total_amount');

        $this->returnedOrders = Order::query()
            ->where('order_status', '=', Status::Returned)
            ->count();

        $this->deliveredOrders = Order::query()
            ->where('order_status', '=', Status::Delivered)
            ->count();
        $this->partiallyDeliveredOrders = Order::query()
            ->where('order_status', '=', Status::PartiallyDelivered)
            ->count();
        $this->approvedShares = Share::query()
            ->where('status', '=', Status::Approved)
            ->count();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboards.sales-manager-dashboard');
    }
}
