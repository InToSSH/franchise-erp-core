<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'sku' => ['required', 'alpha_dash:ascii'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'ean' => ['nullable'],
            'description' => ['nullable'],
            'weight' => ['numeric'],
            'price' => ['required', 'numeric'],
            'qty_in_pack' => ['numeric'],
            'image' => ['nullable', 'image'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
