<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Resources;

use App\Domain\Catalog\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Supplier */
class SupplierOptionsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'value' => $this->id,
        ];
    }
}
