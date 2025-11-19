<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Catalog\Actions\Category\GetTree;
use App\Domain\Catalog\Actions\Product\CreateProduct;
use App\Domain\Catalog\Actions\Product\UpdateProductImage;
use App\Domain\Catalog\Models\Product;
use App\Domain\Catalog\Models\Supplier;
use App\Domain\Catalog\Requests\ProductRequest;
use App\Domain\Catalog\Resources\ProductResource;
use App\Domain\Catalog\Resources\SupplierOptionsResource;
use App\Domain\Catalog\Resources\SupplierResource;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(GetTree $getTree)
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::query();
        if (!empty(request()->query('category_id'))) {
            $query->where('category_id', request()->query('category_id'));
        }

        $products = $this->getDatatableResults(
            $query->with(['category', 'supplier']),
            'name',
            ['name', 'sku', 'ean', 'category.name'],
            ['name', 'sku', 'ean', 'category.name', 'price']
        );

        return Inertia::render('Catalog/Products/Index',
            [
                'products' => ProductResource::collection($products),
                'categories' => $getTree->asTreeNodes(),
                'suppliers' => SupplierOptionsResource::collection(Supplier::all())->toArray(request())
            ]
        );
    }

    public function store(ProductRequest $request, CreateProduct $createProduct, UpdateProductImage $updateProductImage)
    {
        $this->authorize('create', Product::class);
        $product = $createProduct->execute($request->validated());
        if ($request->hasFile('image')) {
            $updateProductImage->execute($product, $request->file('image'));
        }
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product, UpdateProductImage $updateProductImage)
    {
        $this->authorize('update', $product);

        $product->update($request->validated());
        if ($request->hasFile('image')) {
            $updateProductImage->execute($product, $request->file('image'));
        }
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json();
    }
}
