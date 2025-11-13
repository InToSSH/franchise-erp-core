<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Policies;

use App\Domain\Catalog\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('catalog.suppliers.view');
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return $user->can('catalog.suppliers.view');
    }

    public function create(User $user): bool
    {
        return $user->can('catalog.suppliers.edit');
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return $user->can('catalog.suppliers.edit');
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return $user->can('catalog.suppliers.edit');
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return $user->can('catalog.suppliers.edit');
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return $user->can('catalog.suppliers.edit');
    }
}
