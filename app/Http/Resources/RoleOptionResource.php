<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Silber\Bouncer\Database\Role;

/** @mixin Role */
class RoleOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'label' => $this->title,
            'value' => $this->name,
        ];
    }
}
