<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine if the user can view the order.
     *
     * Kasir can only view orders from their assigned outlets.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->role === 'kasir'
            && $user->outlets()->where('outlets.id', $order->outlet_id)->exists();
    }

    /**
     * Determine if the user can update the order.
     *
     * Same authorization as view — kasir must be assigned to the order's outlet.
     */
    public function update(User $user, Order $order): bool
    {
        return $this->view($user, $order);
    }
}
