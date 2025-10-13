<?php

namespace App\Livewire\Dashboard\Widgets;

use App\Models\Product;
use Livewire\Component;

class InventoryStatus extends Component
{
    public $totalProducts;
    public $lowStock;

    public function mount(): void
    {
        $this->totalProducts = Product::count();
        $this->lowStock = Product::whereColumn('stock', '<', 'min_stock')->count();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.widgets.inventory-status');
    }
}
