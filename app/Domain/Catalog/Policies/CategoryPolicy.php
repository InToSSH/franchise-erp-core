<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Policies;

use App\Domain\Catalog\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('catalog.categories.view');
    }

    public function view(User $user, Category $category): bool
    {
        return $user->can('catalog.categories.view');
    }

    public function create(User $user): bool
    {
        return $user->can('catalog.categories.edit');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->can('catalog.categories.edit');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->can('catalog.categories.edit');
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->can('catalog.categories.edit');
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->can('catalog.categories.edit');
    }
}
