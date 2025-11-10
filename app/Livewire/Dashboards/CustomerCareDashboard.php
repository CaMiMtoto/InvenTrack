<?php

namespace App\Livewire\Dashboards;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Livewire\Component;

class CustomerCareDashboard extends Component
{
    public int $newCustomersToday = 0;
    public int $ordersToday = 0;
    public int $pendingOrders = 0;
    public int $totalCustomers = 0;
    public $recentOrders;

    public function mount(): void
    {
        $today = Carbon::today();

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
    }

    public function render()
    {
        return view('livewire.dashboards.customer-care-dashboard');
    }
}
