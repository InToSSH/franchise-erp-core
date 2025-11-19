<?php

declare(strict_types=1);

namespace App\Domain\Admin\Policies;

use App\Domain\Admin\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('admin.branches.view');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->can('admin.branches.view');
    }

    public function create(User $user): bool
    {
        return $user->can('admin.branches.edit');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->can('admin.branches.edit');
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->can('admin.branches.edit');
    }

    public function restore(User $user, Branch $branch): bool
    {
        return $user->can('admin.branches.edit');
    }

    public function forceDelete(User $user, Branch $branch): bool
    {
        return $user->can('admin.branches.edit');
    }
}
