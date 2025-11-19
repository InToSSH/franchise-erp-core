<?php

declare(strict_types=1);

namespace App\Domain\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderMail extends Model
{
    protected $fillable = [
        'order_id',
        'recipient_email',
        'mailable_class',
        'email_content',
        'sent_successfully',
        'error_message',
        'sent_at',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
