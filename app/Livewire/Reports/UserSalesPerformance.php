<?php

namespace App\Livewire\Reports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UserSalesPerformance extends Component
{
    public $startDate;
    public $endDate;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        $performance = User::select(
            'users.id as user_id',
            'users.name as user_name',
            DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
            DB::raw('SUM(order_items.quantity) as total_items'),
            DB::raw('ROUND(SUM(order_items.quantity * order_items.unit_price * COALESCE(product_classes.rate, 0) / 100), 0) as performance_score')
        )
            ->join('orders', 'orders.created_by', '=', 'users.id')
            ->join('order_items', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('product_classes', 'product_classes.id', '=', 'products.product_class_id')
            ->when($this->startDate && $this->endDate, fn($query) => $query->whereBetween('orders.order_date', [$this->startDate, $this->endDate])
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('performance_score')
            ->get();

        return view('livewire.reports.user-sales-performance', compact('performance'));
    }
}
