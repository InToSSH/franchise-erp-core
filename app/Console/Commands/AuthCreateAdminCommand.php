<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Bouncer;
use Carbon\Carbon;
use Hash;
use Illuminate\Console\Command;

class AuthCreateAdminCommand extends Command
{
    protected $signature = 'auth:create-admin';

    protected $description = 'Create an admin user';

    public function handle(): void
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password');

        if (!$name || !$email || !$password) {
            $this->error('You have to fill in all details');
            return;
        }

        if (!$this->confirm('Do you want to create user: ' . $name . ' <' . $email . '>?')) {
            return;
        }

        $user = User::make([
            'name' => $name,
            'email' => $email,
        ]);

        $user->email_verified_at = Carbon::now();
        $user->password = Hash::make($password);
        $user->save();

        Bouncer::assign('admin')->to($user);
        Bouncer::allow($user)->everything();

        $this->info('Admin has been created');
    }
}
