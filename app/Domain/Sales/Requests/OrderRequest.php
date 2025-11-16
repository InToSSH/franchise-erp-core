<?php

declare(strict_types=1);

namespace App\Domain\Sales\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'increment_number' => ['required'],
            'custom_number' => ['nullable'],
            'note' => ['nullable'],
            'status' => ['required'],
            'branch_id' => ['required', 'exists:branches'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable', 'exists:order_items,id'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
