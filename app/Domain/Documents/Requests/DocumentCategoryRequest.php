<?php

declare(strict_types=1);

namespace App\Domain\Documents\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->isMethod('POST') ? $this->store() : $this->update();
    }

    protected function store(): array
    {
        return [
            'name' => ['required'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    protected function update(): array
    {
        return [
            'name' => [],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
