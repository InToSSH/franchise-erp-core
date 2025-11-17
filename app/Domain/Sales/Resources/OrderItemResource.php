<?php

declare(strict_types=1);

namespace App\Domain\Sales\Resources;

use App\Domain\Catalog\Resources\ProductOptionsResource;
use App\Domain\Catalog\Resources\ProductResource;
use App\Domain\Sales\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,

            'order' => new OrderResource($this->whenLoaded('order')),
            'product' => new ProductOptionsResource($this->whenLoaded('product')),
        ];
    }
}
