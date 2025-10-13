<?php

namespace App\Livewire\Dashboard\Widgets;

use App\Models\Customer;
use Livewire\Component;

class CustomerStats extends Component
{
    public $totalCustomers;
    public $activeCustomers;

    public function mount(): void
    {
        $this->totalCustomers = Customer::count();
        $this->activeCustomers = Customer::whereHas('orders', function ($q) {
            $q->where('created_at', '>=', now()->subMonth());
        })->count();
    }

    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboard.widgets.customer-stats');
    }
}
