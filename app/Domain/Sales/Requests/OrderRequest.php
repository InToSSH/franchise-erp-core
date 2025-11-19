<?php

declare(strict_types=1);

namespace App\Domain\Sales\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'custom_number' => ['nullable'],
            'note' => ['nullable'],
            'branch_id' => ['required', 'exists:branches,id'],
            'items' => ['required', 'array'],
            'items.*.id' => ['nullable', 'exists:order_items,id'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => __('Pobočka je povinná.'),
            'branch_id.exists' => __('Vybraná pobočka neexistuje.'),
            'items.required' => __('Položky objednávky jsou povinné.'),
            'items.array' => __('Položky objednávky musí být pole.'),
            'items.*.product_id.required' => __('Musíte vybrat nějaký produkt.'),
            'items.*.product_id.exists' => __('Vybraný produkt neexistuje.'),
            'items.*.quantity.required' => __('Množství je povinné.'),
            'items.*.quantity.numeric' => __('Množství musí být číslo.'),
            'items.*.quantity.min' => __('Množství musí být alespoň 1.'),
        ];
    }
}
