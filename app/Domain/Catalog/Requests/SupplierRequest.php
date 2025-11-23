<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->isMethod('POST') ? $this->store() : $this->update();
    }

    protected function store(): array
    {
        return [
            'name' => ['required'],
            'code' => ['nullable', 'unique:suppliers,code', 'alpha_dash:ascii'],
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

    protected function update(): array
    {
        return [
            'name' => ['required'],
            'code' => ['required', 'unique:suppliers,code', 'alpha_dash:ascii'],
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
