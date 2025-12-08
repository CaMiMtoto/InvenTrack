<?php

namespace App\Livewire\Dashboards;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Share;
use App\Models\Shareholder;
use DB;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $totalCustomers;
    public $totalShareholders;
    public $approvedOrders;
    public $approvedShares;
    public $deliveredOrders;
    public $totalSales;
    public $totalExpenses;

    public function mount()
    {
        $this->totalCustomers = Customer::count();
        $this->totalShareholders = Shareholder::count();

        $this->approvedOrders = Order::where('order_status', 'approved')->count();
        $this->deliveredOrders = Order::where('order_status', 'delivered')->count();

        $this->approvedShares = Share::where('status', 'approved')->count();

        // Total Sales = approved orders + approved shares
        $ordersSales =  \DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.order_status', ['paid', 'completed'])
            ->selectRaw('SUM(quantity * unit_price) as total')
            ->value('total') ?? 0;

        $sharesSales = Share::where('status', 'approved')
            ->sum(DB::raw('value * quantity'));

        $this->totalSales = $ordersSales + $sharesSales;

        $this->totalExpenses = Expense::sum('amount');
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboards.admin-dashboard');
    }
}
