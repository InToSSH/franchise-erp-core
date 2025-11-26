<?php

declare(strict_types=1);

namespace App\Domain\Documents\Policies;

use App\Domain\Documents\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('documents.documents.view');
    }

    public function view(User $user, Document $document): bool
    {
        return $user->can('documents.documents.view');
    }

    public function create(User $user): bool
    {
        return $user->can('documents.documents.edit');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->can('documents.documents.edit');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->can('documents.documents.edit');
    }

    public function restore(User $user, Document $document): bool
    {
        return $user->can('documents.documents.edit');
    }

    public function forceDelete(User $user, Document $document): bool
    {
        return $user->can('documents.documents.edit');
    }
}
