<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domain\Catalog\Actions\Category\GetTree;
use App\Domain\Catalog\Actions\Category\MoveCategory;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Requests\CategoryMoveRequest;
use App\Domain\Catalog\Resources\CategoryResource;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function getTree(?Category $parent, GetTree $getTree)
    {
        $this->authorize('viewAny', Category::class);
        return $getTree->asTreeNodes($parent->id ? $parent : null);
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
