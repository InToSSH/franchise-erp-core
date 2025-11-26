<?php

declare(strict_types=1);

namespace App\Domain\Documents\Policies;

use App\Domain\Documents\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('documents.categories.view');
    }

    public function view(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('documents.categories.view');
    }

    public function create(User $user): bool
    {
        return $user->can('documents.categories.edit');
    }

    public function update(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('documents.categories.edit');
    }

    public function delete(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('documents.categories.edit');
    }

    public function restore(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('documents.categories.edit');
    }

    public function forceDelete(User $user, DocumentCategory $documentCategory): bool
    {
        return $user->can('documents.categories.edit');
    }
}
