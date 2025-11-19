<?php

declare(strict_types=1);

namespace App\Domain\Sales\Jobs;

use App\Domain\Sales\Actions\SendOrderToSuppliers;
use App\Domain\Sales\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderToSuppliersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Order $order)
    {
    }

    public function handle(SendOrderToSuppliers $sendOrderToSuppliers): void
    {
        $sendOrderToSuppliers->execute($this->order);
    }
}
