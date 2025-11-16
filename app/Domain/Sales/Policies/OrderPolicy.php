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
        return $user->can('sales.orders.view');
    }

    public function create(User $user): bool
    {
        return $user->can('sales.orders.edit');
    }

    public function update(User $user, Order $order): bool
    {
        return $user->can('sales.orders.edit');
    }

    public function delete(User $user, Order $order): bool
    {
        return false;
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->can('sales.orders.edit');
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
