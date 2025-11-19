<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions\OrderStatuses;

use App\Domain\Sales\Enums\OrderStatusEnum;
use App\Domain\Sales\Jobs\SendOrderToSuppliersJob;
use App\Domain\Sales\Models\Order;

class ApproveOrder
{
    /**
     * Approve order
     *
     * @param Order $order
     * @return void
     */
    public function execute(Order $order): void
    {
        if (!$order->status->isTransitionAllowed(OrderStatusEnum::APPROVED)) {
            throw new \InvalidArgumentException(__('Objednávku ve stavu ":status" nelze přepnout do stavu ":to_status".', [
                'status' => $order->status->label(),
                'to_status' => OrderStatusEnum::APPROVED->label(),
            ]));
        }

        $order->status = OrderStatusEnum::APPROVED;
        $order->approved_by = auth()->id();
        $order->approved_at = now();
        $order->save();

        SendOrderToSuppliersJob::dispatch($order);
    }
}
