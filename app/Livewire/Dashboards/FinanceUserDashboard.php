<?php

namespace App\Livewire\Dashboards;

use App\Models\Expense;
use App\Models\Share;
use App\Models\User;
use DB;
use Livewire\Component;

class FinanceUserDashboard extends Component
{
    public $totalSales = 0;
    public $totalGoodsSales = 0;
    public $totalShareSales = 0;

    public $totalExpenses = 0;
    public $totalPurchases = 0;

    public $staffPerformance = [];

    public function mount(): void
    {
        $this->calculateSales();
        $this->calculateExpenses();
        $this->calculatePurchases();
        $this->calculateStaffPerformance();
    }

    private function calculateSales(): void
    {
        // Goods sales from order items
        $this->totalGoodsSales = \DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereIn('orders.order_status', ['paid', 'completed'])
            ->selectRaw('SUM(quantity * unit_price) as total')
            ->value('total') ?? 0;

        // Shares sales
        $this->totalShareSales =  Share::where('status', 'approved')
            ->sum(DB::raw('value * quantity'));

        $this->totalSales = $this->totalGoodsSales + $this->totalShareSales;
    }

    private function calculateExpenses(): void
    {
        $this->totalExpenses = Expense::sum('amount') ?? 0;
    }

    private function calculatePurchases(): void
    {
        // Total purchase value from purchase_items
        $this->totalPurchases = \DB::table('purchase_items')
            ->selectRaw('SUM(quantity * unit_price) as total')
            ->value('total') ?? 0;
    }

    private function calculateStaffPerformance(): void
    {
        // Example: Total goods sales per staff (order.created_by)
        $this->staffPerformance = User::select('id', 'name')
            ->withSum(['orders as total_sales' => function ($q) {
                $q->join('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->select(\DB::raw('SUM(order_items.quantity * order_items.unit_price)'));
            }], 'orders.id')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboards.finance-user-dashboard', [
            'totalSales' => $this->totalSales,
            'totalGoodsSales' => $this->totalGoodsSales,
            'totalShareSales' => $this->totalShareSales,
            'totalExpenses' => $this->totalExpenses,
            'totalPurchases' => $this->totalPurchases,
            'staffPerformance' => $this->staffPerformance,
        ]);
    }
}
