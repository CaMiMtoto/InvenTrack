<?php

namespace App\Livewire\Dashboards;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeliveryPersonDashboard extends Component
{
    public int $assignedToday = 0;
    public int $deliveredToday = 0;
    public int $pendingToday = 0;
    public $todaysDeliveries;

    public function mount(): void
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Base query for today's deliveries for the current user
        $query = Delivery::query()
            ->where('delivery_person_id','=',\auth()->id())
            ->whereDate('created_at','>=', $today);

        // Clone the query to get all of today's deliveries for the list
        $this->todaysDeliveries = $query->clone()->with('order.customer')->get();

        // Get the total count of assigned deliveries for today
        $this->assignedToday = $this->todaysDeliveries->count();

        // Get the count of deliveries marked as 'delivered' today
        $this->deliveredToday = $query->clone()->where('status', '=','delivered')->count();

        // Get the count of deliveries with a 'pending' status for today
        $this->pendingToday = $query->clone()->where('status', 'pending')->count();
    }


    public function render()
    {
        return view('livewire.dashboards.delivery-person-dashboard');
    }
}
