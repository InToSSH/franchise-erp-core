<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GenerateEntityCode;
use App\Domain\Admin\Models\Branch;
use App\Domain\Catalog\Requests\SupplierRequest;
use App\Domain\Catalog\Resources\SupplierResource;
use App\Domain\Catalog\Models\Supplier;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Supplier::class);

        $suppliers = $this->getDatatableResults(
            Supplier::query(),
            'name',
            ['name', 'email', 'phone', 'street', 'city'],
            ['name', 'email', 'phone', 'street', 'city']
        );

        return Inertia::render(
            'Catalog/Suppliers/Index',
            [
                'suppliers' => SupplierResource::collection($suppliers),
            ]
        );
    }

    public function store(SupplierRequest $request, GenerateEntityCode $generateEntityCode)
    {
        $this->authorize('create', Supplier::class);

        $data = $request->validated();
        if (empty($data['code'])) {
            $data['code'] = $generateEntityCode->execute(Branch::class, 'code', $data['name']);
        }

        Supplier::create($data);
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view', $supplier);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $supplier->update($request->validated());
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);
        return $this->destroyModel($supplier);
    }
}
