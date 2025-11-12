<?php

declare(strict_types=1);

namespace App\Domain\Admin\Resources;

use App\Domain\Admin\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Branch */
class BranchOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'label' => $this->name,
            'value' => $this->id,
        ];
    }
}
