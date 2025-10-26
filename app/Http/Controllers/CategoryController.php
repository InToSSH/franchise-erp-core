<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Requests\CategoryRequest;
use App\Domain\Catalog\Resources\CategoryResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Category::class);

        return CategoryResource::collection(Category::all());
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        return new CategoryResource(Category::create($request->validated()));
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return response()->json();
    }
}
