<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Policies;

use App\Domain\Catalog\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('catalog.products.view');
    }

    public function view(User $user, Product $product): bool
    {
        return $user->can('catalog.products.view');
    }

    public function create(User $user): bool
    {
        return $user->can('catalog.products.edit');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->can('catalog.products.edit');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->can('catalog.products.edit');
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->can('catalog.products.edit');
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->can('catalog.products.edit');
    }
}
