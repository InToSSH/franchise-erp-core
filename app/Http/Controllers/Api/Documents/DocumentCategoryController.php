<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Documents;

use App\Domain\Documents\Actions\DocumentCategory\GetTree;
use App\Domain\Documents\Actions\DocumentCategory\MoveCategory;
use App\Domain\Documents\Requests\DocumentCategoryMoveRequest;
use App\Domain\Documents\Resources\DocumentCategoryResource;
use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;

class DocumentCategoryController extends Controller
{
    public function getTree(?DocumentCategory $parent, GetTree $getTree)
    {
        $this->authorize('viewAny', DocumentCategory::class);
        return $getTree->asTreeNodes($parent->id ? $parent : null);
    }

    public function move(DocumentCategory $category, DocumentCategoryMoveRequest $categoryMoveRequest, MoveCategory $moveCategory)
    {
        $this->authorize('update', $category);

        $moveCategory->execute(
            $category,
            $categoryMoveRequest->validated('parent_id'),
            $categoryMoveRequest->validated('after_id'),
            $categoryMoveRequest->validated('before_id')
        );

        return new DocumentCategoryResource($category);
    }
}
