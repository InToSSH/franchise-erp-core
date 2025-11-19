<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions\OrderStatuses;

use App\Domain\Sales\Enums\OrderStatusEnum;
use App\Domain\Sales\Models\Order;

class SetOrderForApproval
{
    /**
     * Set order status to awaiting approval
     *
     * @param Order $order
     * @return void
     */
    public function execute(Order $order): void
    {
        if (!$order->status->isTransitionAllowed(OrderStatusEnum::AWAITING_APPROVAL)) {
            throw new \InvalidArgumentException(__('Objednávku ve stavu ":status" nelze přepnout do stavu ":to_status".', [
                'status' => $order->status->label(),
                'to_status' => OrderStatusEnum::AWAITING_APPROVAL->label(),
            ]));
        }

        $order->status = OrderStatusEnum::AWAITING_APPROVAL;
        $order->save();
    }
}
