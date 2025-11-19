<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Actions\Product;

use App\Domain\Catalog\Models\Product;

class CreateProduct
{
    public function execute(array $data): Product
    {
        return Product::create($data);
    }

}
