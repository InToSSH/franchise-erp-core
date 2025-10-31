<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Catalog\Actions\Category\GetTree;
use App\Domain\Catalog\Actions\Category\MoveCategory;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Requests\CategoryMoveRequest;
use App\Domain\Catalog\Requests\CategoryRequest;
use App\Domain\Catalog\Resources\CategoryResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class CategoryController extends Controller
{
    use AuthorizesRequests;

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

    public function move(Category $category, CategoryMoveRequest $categoryMoveRequest, MoveCategory $moveCategory)
    {
        $this->authorize('update', $category);

        $moveCategory->execute(
            $category,
            $categoryMoveRequest->validated('parent_id'),
            $categoryMoveRequest->validated('after_id'),
            $categoryMoveRequest->validated('before_id')
        );

        return new CategoryResource($category);
    }
}
