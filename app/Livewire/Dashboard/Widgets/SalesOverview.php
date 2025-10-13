<?php

namespace App\Livewire\Dashboard\Widgets;

use App\Models\Order;
use Livewire\Component;

class SalesOverview extends Component
{
    public $totalSales;
    public $todaySales;

    public function mount(): void
    {
        $this->totalSales = Order::sum('total_amount');
        $this->todaySales = Order::whereDate('created_at', today())->sum('total_amount');
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.widgets.sales-overview');
    }
}
