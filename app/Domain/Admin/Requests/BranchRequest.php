<?php

declare(strict_types=1);

namespace App\Domain\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
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
            'email' => ['nullable', 'email', 'max:254'],
            'phone' => ['nullable'],
            'cin' => ['nullable'],
            'tin' => ['nullable'],
            'street' => ['nullable'],
            'city' => ['nullable'],
            'post_code' => ['nullable'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    protected function update(): array
    {
        return [
            'name' => ['required'],
            'code' => ['required', 'unique:suppliers,code', 'alpha_dash:ascii'],
            'email' => ['nullable', 'email', 'max:254'],
            'phone' => ['nullable'],
            'cin' => ['nullable'],
            'tin' => ['nullable'],
            'street' => ['nullable'],
            'city' => ['nullable'],
            'post_code' => ['nullable'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
