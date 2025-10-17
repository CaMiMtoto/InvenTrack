<?php

namespace App\Policies;

use App\Constants\Permission;
use App\Constants\Status;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can mark the order as complete.
     *
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function complete(User $user, Order $order): bool
    {
        return $order->canBeCompleted();
    }
}
