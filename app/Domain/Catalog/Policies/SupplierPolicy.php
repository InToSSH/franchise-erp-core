<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Policies;

use App\Domain\Supplier\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Supplier $supplier): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Supplier $supplier): bool
    {
        return true;
    }

    public function delete(User $user, Supplier $supplier): bool
    {
        return true;
    }

    public function restore(User $user, Supplier $supplier): bool
    {
        return true;
    }

    public function forceDelete(User $user, Supplier $supplier): bool
    {
        return true;
    }
}
