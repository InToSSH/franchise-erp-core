<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Requests\ProductRequest;
use App\Domain\Catalog\Resources\ProductResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->getDatatableResults(
            Product::query(),
            'name',
            ['name', 'sku', 'ean', 'category.name'],
            ['name', 'sku', 'ean', 'category.name', 'price']
        );

        return Inertia::render('Catalog/Products/Index',
            [
                'products' => ProductResource::collection($products),
            ]
        );
    }

    public function store(ProductRequest $request)
    {
        $this->authorize('create', Product::class);

        return new ProductResource(Product::create($request->validated()));
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json();
    }
}
