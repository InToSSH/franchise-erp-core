<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions\Category;

use App\Domain\Catalog\Models\Category;

class UpdateCategory
{
    public function execute(Category $category, array $data): Category
    {
        return tap($category)->update($data);
    }
}
