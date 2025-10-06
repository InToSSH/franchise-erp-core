<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'code' => ['nullable'],
            'contact_person' => ['nullable'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable'],
            'street' => ['nullable'],
            'city' => ['nullable'],
            'post_code' => ['nullable'],
            'cin' => ['nullable'],
            'tin' => ['nullable'],
            'bank_account' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
