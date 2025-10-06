<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Catalog\Requests\SupplierRequest;
use App\Domain\Catalog\Resources\SupplierResource;
use App\Domain\Catalog\Models\Supplier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class SupplierController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Supplier::class);

        return Inertia::render(
            'Catalog/Suppliers/Index',
            [
                'suppliers' => SupplierResource::collection(Supplier::paginate(10)),
            ]
        );
    }

    public function store(SupplierRequest $request)
    {
        $this->authorize('create', Supplier::class);

        return new SupplierResource(Supplier::create($request->validated()));
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view', $supplier);

        return new SupplierResource($supplier);
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('update', $supplier);

        $supplier->update($request->validated());

        return new SupplierResource($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete', $supplier);

        $supplier->delete();

        return response()->json();
    }
}
