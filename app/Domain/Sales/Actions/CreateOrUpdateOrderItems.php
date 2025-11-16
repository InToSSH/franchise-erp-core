<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions;

use App\Domain\Sales\Models\Order;

class CreateOrUpdateOrderItems
{
    public function execute(Order $order, array $itemsData): void
    {
        foreach ($itemsData as $itemData) {
            $orderItem = $order->items()->updateOrCreate(
                ['id' => $itemData['id'] ?? null],
                $itemData
            );
        }

        foreach ($order->items as $item) {
            if (!in_array($item->id, array_column($itemsData, 'id'))) {
                $item->delete();
            }
        }
    }
}
