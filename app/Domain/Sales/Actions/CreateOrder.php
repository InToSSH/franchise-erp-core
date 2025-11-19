<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions;

use App\Domain\Sales\Enums\OrderStatusEnum;
use App\Domain\Sales\Models\Order;
use DB;

class CreateOrder
{
    public function __construct(
        private readonly GenerateIncrementNumber $generateIncrementNumber,
        private readonly CreateOrUpdateOrderItems $createOrUpdateOrderItems
    ) {
    }

    /**
     * @param array $data
     * @return Order
     */
    public function execute(array $data): Order
    {
        $order = new Order();
        $order->increment_number = $this->generateIncrementNumber->execute(Order::class, 'increment_number');
        $order->status = OrderStatusEnum::DEFAULT;
        $order->created_by = auth()->id();

        $itemsData = $data['items'] ?? [];
        unset($data['items']);

        $order->fill($data);
        $order->save();

        $this->createOrUpdateOrderItems->execute($order, $itemsData);

        return $order;
    }
}
