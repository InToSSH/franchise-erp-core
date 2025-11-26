<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions\DocumentCategory;

use App\Actions\GenerateEntityCode;
use App\Domain\Documents\Models\DocumentCategory;
use LogicException;

class CreateCategory
{
    public function __construct(
        private readonly MoveCategory $moveCategory,
        private readonly GenerateEntityCode $generateEntityCode
    ) {
    }

    public function execute(array $data): DocumentCategory
    {
        $category = DocumentCategory::create($data);

        try {
            $this->moveCategory->execute(
                $category,
                $data['parent_id'] ?? null,
                $data['after_id'] ?? null,
                $data['before_id'] ?? null
            );
        } catch (LogicException $e) {
            abort(400, $e->getMessage());
        }

        return $category;
    }
}
