<?php

declare(strict_types=1);

namespace App\Domain\Documents\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentCategoryMoveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'after_id' => ['nullable', 'integer', 'exists:categories,id'],
            'before_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }
}
