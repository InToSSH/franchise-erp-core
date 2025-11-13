<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\CreateUser;
use App\Actions\User\UpdateUser;
use App\Domain\Admin\Models\Branch;
use App\Domain\Admin\Resources\BranchOptionResource;
use App\Http\Requests\UserRequest;
use App\Http\Resources\RoleOptionResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Bouncer;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view-any', User::class);

        $users = $this->getDatatableResults(
            User::query()->with('branches'),
            'name',
            ['name', 'email', 'created_at'],
            ['name', 'email']
        );

        return inertia('Admin/Users/Index', [
            'users' => UserResource::collection($users),
            'branches' => BranchOptionResource::collection(Branch::orderBy('name')->get())->toArray(request()),
            'roles' => RoleOptionResource::collection(Bouncer::role()->get())->toArray(request()),
        ]);
    }

    public function store(UserRequest $request, CreateUser $createUser)
    {
        $this->authorize('create', User::class);

        $createUser->execute($request->validated());
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
    }

    public function update(UserRequest $request, User $user, UpdateUser $updateUser)
    {
        $this->authorize('update', $user);
        $updateUser->execute($user, $request->validated());
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $this->destroyModel($user);
    }
}
