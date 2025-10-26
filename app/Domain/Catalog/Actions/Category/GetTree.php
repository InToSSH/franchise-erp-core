<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions\Category;

use App\Domain\Catalog\Models\Category;

class GetTree
{
    /**
     * Get the category tree or subtree.
     *
     * @param Category|null $parent
     * @return Category[]
     */
    public function execute(?Category $parent = null)
    {
        if ($parent) {
            return Category::defaultOrder()->descendantsAndSelf($parent->id)->toTree()->first();
        }

        return Category::defaultOrder()->get()->toTree();
    }
}
