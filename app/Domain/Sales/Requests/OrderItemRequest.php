<?php

declare(strict_types=1);

namespace App\Domain\Sales\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders'],
            'product_id' => ['required', 'exists:products'],
            'quantity' => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
