<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions;

use App\Domain\Sales\Models\Order;

class UpdateOrder
{
    public function __construct(
        private readonly CreateOrUpdateOrderItems $createOrUpdateOrderItems
    ) {
    }

    /**
     * @param Order $order
     * @param array $data
     * @return Order
     */
    public function execute(Order $order, array $data): Order
    {
        $itemsData = $data['items'] ?? [];
        unset($data['items']);

        $order->fill($data);
        $order->save();

        $this->createOrUpdateOrderItems->execute($order, $itemsData);

        return $order;
    }
}
