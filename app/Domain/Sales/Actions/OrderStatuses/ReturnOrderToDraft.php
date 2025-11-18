<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions\OrderStatuses;

use App\Domain\Sales\Enums\OrderStatusEnum;
use App\Domain\Sales\Models\Order;

class ReturnOrderToDraft
{
    /**
     * Return order to draft status
     *
     * @param Order $order
     * @return void
     */
    public function execute(Order $order): void
    {
        if (!$order->status->isTransitionAllowed(OrderStatusEnum::DRAFT)) {
            throw new \InvalidArgumentException(__('Objednávku ve stavu ":status" nelze přepnout do stavu ":to_status".', [
                'status' => $order->status->label(),
                'to_status' => OrderStatusEnum::DRAFT->label(),
            ]));
        }

        $order->status = OrderStatusEnum::DRAFT;
        $order->approved_by = null;
        $order->approved_at = null;
        $order->save();
    }
}
