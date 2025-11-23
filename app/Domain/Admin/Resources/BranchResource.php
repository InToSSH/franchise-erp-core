<?php

declare(strict_types=1);

namespace App\Domain\Admin\Resources;

use App\Domain\Admin\Models\Branch;
use App\Http\Resources\UserOptionResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Branch */
class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'manager_id' => $this->manager_id,
            'manager' => new UserResource($this->whenLoaded('manager')),
            'users' => UserOptionResource::collection($this->whenLoaded('users')),
            'email' => $this->email,
            'phone' => $this->phone,
            'cin' => $this->cin,
            'tin' => $this->tin,
            'street' => $this->street,
            'city' => $this->city,
            'post_code' => $this->post_code,
            'full_address' => $this->full_address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
