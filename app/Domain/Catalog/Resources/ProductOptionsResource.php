<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Resources;

use App\Domain\Catalog\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductOptionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'label' => $this->name,
            'id' => $this->id,
            'sku' => $this->sku,
            'unit_price' => $this->price,
            'image_path' => $this->image_path,
            'category_name' => $this->category?->name,
        ];
    }
}
