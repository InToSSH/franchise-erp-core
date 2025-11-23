<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions\Category;

use App\Actions\GenerateEntityCode;
use App\Domain\Admin\Models\Branch;
use LogicException;
use App\Domain\Catalog\Models\Category;

class CreateCategory
{
    public function __construct(
        private readonly MoveCategory $moveCategory,
        private readonly GenerateEntityCode $generateEntityCode
    ) {
    }

    public function execute(array $data): Category
    {
        if (empty($data['code'])) {
            $data['code'] = $this->generateEntityCode->execute(Category::class, 'code', $data['name']);
        }

        $category = Category::create($data);

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
