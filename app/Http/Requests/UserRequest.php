<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->isMethod('POST') ? $this->store() : $this->update();
    }

    public function store(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed:password_confirmation', 'min:8'],
            'branches' => ['array'],
            'branches.*' => ['integer', 'exists:branches,id'],
        ];
    }

    public function update(): array
    {
        return [
            'name' => [],
            'email' => ['email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed:password_confirmation', 'min:8'],
            'branches' => ['array'],
            'branches.*' => ['integer', 'exists:branches,id'],
        ];
    }
}
