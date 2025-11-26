<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions\DocumentCategory;

use App\Domain\Documents\Models\DocumentCategory;
use App\Domain\Documents\Resources\DocumentCategoryResource;

class GetTree
{
    /**
     * Get the category tree or subtree.
     *
     * @param DocumentCategory|null $parent
     * @return DocumentCategory[]
     */
    public function asDefaultTree(?DocumentCategory $parent = null)
    {
        if ($parent) {
            return DocumentCategory::defaultOrder()->descendantsAndSelf($parent->id)->toTree()->first();
        }

        return DocumentCategory::defaultOrder()->get()->toTree();
    }

    /**
     * Get the category tree as TreeNode object compatible with PrimeVue Tree component.
     *
     * @param DocumentCategory|null $parent
     * @return array
     */
    public function asTreeNodes(?DocumentCategory $parent = null): array
    {
        $categories = $this->asDefaultTree($parent);

        $mapToTreeNode = function (DocumentCategory $category) use (&$mapToTreeNode) {
            return [
                'key' => $category->id,
                'label' => $category->name,
                'icon' => $category->icon,
                'children' => $category->children->map($mapToTreeNode)->toArray(),
                'data' => (new DocumentCategoryResource($category))->toArray(request()),
            ];
        };

        return collect($categories)->map($mapToTreeNode)->toArray();
    }
}
