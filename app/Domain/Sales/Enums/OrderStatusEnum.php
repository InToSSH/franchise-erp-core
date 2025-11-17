<?php

declare(strict_types=1);

namespace App\Domain\Sales\Enums;

enum OrderStatusEnum: string
{
    case DRAFT = 'draft';
    case AWAITING_APPROVAL = 'awaiting_approval';
    case APPROVED = 'approved';
    case ORDERED = 'ordered';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';

    public const DEFAULT = self::DRAFT;

    /**
     * Get a human-readable label for the order status in czech language
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Koncept'),
            self::AWAITING_APPROVAL => __('Čeká na schválení'),
            self::APPROVED => __('Schváleno'),
            self::ORDERED => __('Objednáno'),
            self::DELIVERED => __('Doručeno'),
            self::CANCELED => __('Zrušeno'),
        };
    }

    public static function getOptions(): array
    {
        $values = [];
        foreach (static::cases() as $case) {
            $values[] = $case->toOption();
        }

        return $values;
    }

    public function toOption(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }

    /**
     * Get allowed transitions from one status to another
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::AWAITING_APPROVAL, self::CANCELED],
            self::AWAITING_APPROVAL => [self::APPROVED, self::CANCELED],
            self::APPROVED => [self::ORDERED, self::CANCELED],
            self::ORDERED => [self::DELIVERED, self::CANCELED],
            self::DELIVERED => [],
            self::CANCELED => [],
        };
    }

}
