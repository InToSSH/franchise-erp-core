<?php

declare(strict_types=1);

namespace App\Domain\Sales\Models;

use App\Domain\Admin\Models\Branch;
use App\Domain\Sales\Enums\OrderStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'increment_number',
        'custom_number',
        'note',
        'status',
        'branch_id',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function totalPrice(): Attribute
    {
        return Attribute::get(
            fn () => $this->items()->sum('total_price')
        );
    }
}
