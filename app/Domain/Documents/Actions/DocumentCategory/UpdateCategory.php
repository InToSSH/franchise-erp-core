<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions\DocumentCategory;

use App\Models\DocumentCategory;

class UpdateCategory
{
    public function execute(DocumentCategory $category, array $data): DocumentCategory
    {
        return tap($category)->update($data);
    }
}
