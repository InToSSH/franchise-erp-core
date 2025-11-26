<?php

declare(strict_types=1);

namespace App\Domain\Documents\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'content' => ['required'],
            'is_direct_download' => ['boolean'],
            'created_by' => ['required', 'exists:users'],
            'document_category_id' => ['required', 'exists:document_categories'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
