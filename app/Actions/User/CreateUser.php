<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;
use Bouncer;

class CreateUser
{
    public function execute(array $data): User
    {
        $user = User::create($data);

        if (!empty($data['roles'])) {
            Bouncer::sync($user)->roles($data['roles']);
        }

        return $user;
    }
}
