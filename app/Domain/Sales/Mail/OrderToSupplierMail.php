<?php

declare(strict_types=1);

namespace App\Domain\Sales\Mail;

use App\Domain\Catalog\Models\Supplier;
use App\Domain\Sales\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class OrderToSupplierMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly Supplier $supplier,
        public readonly Collection $items,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Objednávka č. ' . $this->order->increment_number . ' od OOO Pizza',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-to-supplier',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
