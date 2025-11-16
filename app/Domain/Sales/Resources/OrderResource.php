<?php

declare(strict_types=1);

namespace App\Domain\Sales\Resources;

use App\Domain\Admin\Resources\BranchResource;
use App\Domain\Sales\Models\Order;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'increment_number' => $this->increment_number,
            'custom_number' => $this->custom_number,
            'note' => $this->note,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'branch_id' => $this->branch_id,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,

            'branch' => new BranchResource($this->whenLoaded('branch')),
            'createdBy' => new UserResource($this->whenLoaded('createdBy')),
            'approvedBy' => new UserResource($this->whenLoaded('approvedBy')),
        ];
    }
}
