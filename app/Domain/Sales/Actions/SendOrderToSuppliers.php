<?php

declare(strict_types=1);

namespace App\Domain\Sales\Actions;

use App\Domain\Sales\Enums\OrderStatusEnum;
use App\Domain\Sales\Mail\OrderToSupplierMail;
use App\Domain\Sales\Models\Order;
use Illuminate\Support\Facades\Mail;

class SendOrderToSuppliers
{
    /**
     * Send order details to each supplier involved in the order.
     *
     * @param Order $order
     * @return void
     */
    public function execute(Order $order): void
    {
        $suppliers = $order->items()
            ->with('product.supplier')
            ->get()
            ->pluck('product.supplier')
            ->unique('id');

        foreach ($suppliers as $supplier) {
            $items = $order->items()
                ->with('product')
                ->whereHas('product', fn($q) => $q->where('supplier_id', $supplier->id))
                ->get();

            $orderMail = $order->orderMail()->create(
                [
                    'recipient_email' => $supplier->email,
                    'mailable_class' => OrderToSupplierMail::class,
                    'email_content' => json_encode([
                        'supplier_id' => $supplier->id,
                        'items_count' => $items->count(),
                        'items' => $items->map(fn($item) => [
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'total_price' => $item->total_price,
                        ]),
                    ]),
                ]
            );

            try {
                Mail::to($supplier->email)
                    ->send(new OrderToSupplierMail($order, $supplier, $items));
            } catch (\Exception $e) {
                $orderMail->update([
                    'sent_successfully' => false,
                    'error_message' => $e->getMessage(),
                ]);
                continue;
            }

            $orderMail->update([
                'sent_successfully' => true,
                'sent_at' => now(),
            ]);
        }

        $order->status = OrderStatusEnum::ORDERED;
        $order->save();
    }
}
