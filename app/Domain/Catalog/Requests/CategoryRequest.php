<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->isMethod('POST') ? $this->store() : $this->update();
    }

    protected function store(): array
    {
        return [
            'name' => ['required'],
            'code' => ['required', 'unique:categories,code', 'alpha_dash:ascii'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    protected function update(): array
    {
        return [
            'name' => [],
            'code' => ['unique:categories,code,' . $this->category->id, 'alpha_dash:ascii'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
