<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions\Category;

use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Resources\CategoryResource;

class GetTree
{
    /**
     * Get the category tree or subtree.
     *
     * @param Category|null $parent
     * @return Category[]
     */
    public function asDefaultTree(?Category $parent = null)
    {
        if ($parent) {
            return Category::defaultOrder()->descendantsAndSelf($parent->id)->toTree()->first();
        }

        return Category::defaultOrder()->get()->toTree();
    }

    /**
     * Get the category tree as TreeNode object compatible with PrimeVue Tree component.
     *
     * @param Category|null $parent
     * @return array
     */
    public function asTreeNodes(?Category $parent = null): array
    {
        $categories = $this->asDefaultTree($parent);

        $mapToTreeNode = function (Category $category) use (&$mapToTreeNode) {
            return [
                'key' => $category->id,
                'label' => $category->name,
                'icon' => $category->icon,
                'children' => $category->children->map($mapToTreeNode)->toArray(),
                'data' => (new CategoryResource($category))->toArray(request()),
            ];
        };

        return collect($categories)->map($mapToTreeNode)->toArray();
    }
}
