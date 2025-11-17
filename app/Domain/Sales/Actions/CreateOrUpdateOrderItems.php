<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions;

use App\Domain\Catalog\Models\Product;
use App\Domain\Sales\Models\Order;

class CreateOrUpdateOrderItems
{
    public function execute(Order $order, array $itemsData): void
    {
        foreach ($itemsData as $key => $itemData) {
            $product = Product::find($itemData['product_id']);
            $orderItem = $order->items()->updateOrCreate(
                ['id' => $itemData['id'] ?? null],
                [
                    ...$itemData,
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $itemData['quantity'],
                ]
            );

            $itemsData[$key]['id'] = $orderItem->id;
        }

        foreach ($order->items as $item) {
            if (!in_array($item->id, array_column($itemsData, 'id'))) {
                $item->delete();
            }
        }
    }
}
