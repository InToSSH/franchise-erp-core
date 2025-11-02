<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Catalog\Actions\Category\GetTree;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Requests\CategoryRequest;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(GetTree $getTree)
    {
        $this->authorize('viewAny', Category::class);

        return Inertia::render(
            'Catalog/Categories/Index',
            [
                'categories' => $getTree->asTreeNodes(),
            ]
        );
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        Category::create($request->validated());
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $category->update($request->validated());
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        return $this->destroyModel($category);
    }
}
