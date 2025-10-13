<?php

namespace App\Livewire\Dashboard;

use App\Constants\Status;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\ReturnReason;
use Illuminate\Support\Carbon;
use Livewire\Component;

class MainDashboard extends Component
{
    public $totalSales;
    public $todaySales;
    public $totalOrders;
    public $deliveredOrders;
    public $totalReturns;
    public $weeklyReturns;
    public $totalProducts;
    public $lowStockProducts;

    public $salesChartData = [];
    public $returnsChartData = [];
    public $topProducts = [];
    protected $listeners = [
        'refreshDashboard' => 'loadData'
    ];
    public function mount(): void
    {
        $this->loadMetrics();
        $this->loadCharts();
        $this->loadTopProducts();
    }
    public function loadTopProducts($limit = 10): void
    {
        // Top-selling products by quantity sold
        $this->topProducts = OrderItem::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->limit($limit)
            ->get();
    }
    public function loadMetrics(): void
    {
        // Sales
        $this->totalSales = Order::sum('total_amount');
        $this->todaySales = Order::whereDate('created_at', today())->sum('total_amount');

        // Orders
        $this->totalOrders = Order::count();
        $this->deliveredOrders = Order::where('order_status', 'DELIVERED')->count();

        // Returns
        $this->totalReturns = ReturnItem::sum('quantity');
        $this->weeklyReturns = ReturnItem::where('created_at', '>=', Carbon::now()->subDays(7))->sum('quantity');

        // Products
        $this->totalProducts = Product::count();
        $this->lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->count();
    }

    public function loadCharts()
    {
        // Sales chart - last 7 days
        $salesData = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            $salesData[] = Order::whereDate('created_at', $date)->sum('total_amount');
        }
        $this->salesChartData = [
            'labels' => $labels,
            'data' => $salesData,
        ];

        // Returns chart by reason
        $reasons = ReturnReason::query()->where('active', '=',true)->get();
        $labels = [];
        $data = [];
        foreach ($reasons as $reason) {
            $labels[] = $reason->name;
            $data[] = ReturnItem::where('return_reason_id', $reason->id)->sum('quantity');
        }
        $this->returnsChartData = [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.main-dashboard');
    }
}
