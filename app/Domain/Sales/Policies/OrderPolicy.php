<?php

declare(strict_types=1);

namespace App\Domain\Sales\Policies;

use App\Domain\Sales\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('sales.orders.view');
    }

    public function viewAll(User $user): bool
    {
        return $user->can('sales.orders.view-all');
    }

    public function view(User $user, Order $order): bool
    {
        return $user->can('sales.orders.view')
            && $user->branches->contains('id', $order->branch_id);
    }

    public function create(User $user): bool
    {
        return $user->can('sales.orders.edit');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('sales.orders.edit')
            && $user->branches->contains('id', $order->branch_id);
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->can('sales.orders.delete');
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->can('sales.orders.edit')
            && $user->branches->contains('id', $order->branch_id);
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->can('sales.orders.delete');
    }

    public function cancel(User $user, Order $order): bool
    {
        return $user->can('sales.orders.edit')
            && $user->branches->contains('id', $order->branch_id);
    }

    public function approve(User $user, Order $order): bool
    {
        return $user->can('sales.orders.approve')
            && $user->branches->contains('id', $order->branch_id);
    }
}
