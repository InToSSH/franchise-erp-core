<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Admin\Resources\BranchOptionResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'branches' => BranchOptionResource::collection($this->whenLoaded('branches')),
            'roles' => RoleOptionResource::collection($this->roles),
            'profile_photo_path' => $this->profile_photo_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'profile_photo_url' => $this->profile_photo_url,
        ];
    }
}
