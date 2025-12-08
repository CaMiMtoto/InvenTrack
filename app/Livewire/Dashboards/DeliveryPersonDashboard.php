<?php

namespace App\Livewire\Dashboards;

use App\Constants\Status;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeliveryPersonDashboard extends Component
{
    public int $totalAssigned = 0;
    public int $delivered = 0;
    public int $partiallyDelivered = 0;
    public $todaysDeliveries;
    public $startDate;
    public $endDate;

    public function mount(): void
    {

        // Base query for today's deliveries for the current user
        $query = Delivery::query()
            ->withCount('items')
            ->where('delivery_person_id', '=', \auth()->id())
            ->when($this->startDate, fn(Builder $query, $startDate) => $query->whereDate('created_at', '>=', $startDate))
            ->when($this->endDate, fn(Builder $query, $endDate) => $query->whereDate('created_at', '<=', $endDate))
            ->latest();

        // Clone the query to get all of today's deliveries for the list
        $this->todaysDeliveries = $query->clone()->with('order.customer')->limit(10)->get();

        // Get the total count of assigned deliveries for today
        $this->totalAssigned = $query->clone()->where('status', '!=', 'pending')->count();

        // Get the count of deliveries marked as 'delivered' today
        $this->delivered = $query->clone()->where('status', '=', Status::Delivered)->count();
        $this->partiallyDelivered = $query->clone()->where('status', '=', Status::PartiallyDelivered)->count();

    }


    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.dashboards.delivery-person-dashboard');
    }
}
