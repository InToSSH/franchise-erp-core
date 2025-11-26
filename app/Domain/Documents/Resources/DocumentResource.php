<?php

declare(strict_types=1);

namespace App\Domain\Documents\Resources;

use App\Domain\Documents\Models\Document;
use App\Http\Resources\UserOptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Document */
class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'is_direct_download' => $this->is_direct_download,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'created_by' => $this->created_by,
            'document_category_id' => $this->document_category_id,

            'createdBy' => new UserOptionResource($this->whenLoaded('createdBy')),
            'documentCategory' => new DocumentCategoryResource($this->whenLoaded('documentCategory')),
        ];
    }
}
