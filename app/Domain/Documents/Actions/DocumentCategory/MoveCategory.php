<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions\DocumentCategory;

use App\Domain\Documents\Models\DocumentCategory;

class MoveCategory
{
    public function execute(DocumentCategory $category, ?int $parentId = null, ?int $afterId = null, ?int $beforeId = null): ?DocumentCategory
    {
        if (!isset($parentId) && !isset($afterId) && !isset($beforeId)) {
            $category->saveAsRoot();
            return $category;
        }

        if (isset($parentId) && $afterId === null && $beforeId === null) {
            if ($parent = DocumentCategory::find($parentId)) {
                $category->appendToNode($parent)->save();
                return $category;
            }
        }

        if (isset($afterId)) {
            if ($sibling = DocumentCategory::find($afterId)) {
                $category->afterNode($sibling)->save();
                return $category;
            }
        }

        if (isset($beforeId)) {
            if ($sibling = DocumentCategory::find($beforeId)) {
                $category->beforeNode($sibling)->save();
                return $category;
            }
        }

        return null;
    }
}
