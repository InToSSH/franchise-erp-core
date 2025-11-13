<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Bouncer;
use Hash;

class UpdateUser
{
    public function execute(User $user, array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->branches()->sync($data['branches'] ?? []);

        if (!empty($data['roles'])) {
            Bouncer::sync($user)->roles($data['roles']);
        }

        $user->update($data);

        return $user;
    }
}
