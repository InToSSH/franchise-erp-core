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
            'sku' => ['required'],
            'supplier_id' => ['required', 'exists:suppliers'],
            'ean' => ['required'],
            'description' => ['required'],
            'weight' => ['required', 'numeric'],
            'qty_unit' => ['required'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
