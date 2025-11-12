<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GenerateEntityCode;
use App\Domain\Admin\Models\Branch;
use App\Domain\Admin\Requests\BranchRequest;
use App\Domain\Admin\Resources\BranchResource;
use Inertia\Inertia;

class BranchController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Branch::class);

        $branches = $this->getDatatableResults(
            Branch::query()->with('users', 'manager'),
            'name',
            ['name', 'code', 'email', 'phone', 'city'],
            ['name', 'code', 'email', 'phone', 'city']
        );

        return Inertia::render(
            'Admin/Branches/Index',
            [
                'branches' => BranchResource::collection($branches),
            ]
        );
    }

    public function store(BranchRequest $request, GenerateEntityCode $generateEntityCode)
    {
        $this->authorize('create', Branch::class);

        $data = $request->validated();
        if (empty($data['code'])) {
            $data['code'] = $generateEntityCode->execute(Branch::class, 'code', $request->input('name'));
        }

        Branch::create($data);
    }

    public function show(Branch $branch)
    {
        $this->authorize('view', $branch);
    }

    public function update(BranchRequest $request, Branch $branch)
    {
        $this->authorize('update', $branch);

        $branch->update($request->validated());
    }

    public function destroy(Branch $branch)
    {
        $this->authorize('delete', $branch);
        return $this->destroyModel($branch);
    }
}
