<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions\Category;

use App\Domain\Catalog\Models\Category;

class MoveCategory
{
    public function execute(Category $category, int $parentId = null, int $afterId = null, int $beforeId = null): ?Category
    {
        if (!isset($parentId) && !isset($afterId) && !isset($beforeId)) {
            $category->saveAsRoot();
            return $category;
        }

        if (isset($parentId)) {
            if ($parent = Category::find($parentId)) {
                $category->appendToNode($parent)->save();
                return $category;
            }
        }

        if (isset($afterId)) {
            if ($sibling = Category::find($afterId)) {
                $category->afterNode($sibling)->save();
                return $category;
            }
        }

        if (isset($beforeId)) {
            if ($sibling = Category::find($beforeId)) {
                $category->beforeNode($sibling)->save();
                return $category;
            }
        }

        return null;
    }
}
