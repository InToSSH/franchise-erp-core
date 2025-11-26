<?php

declare(strict_types=1);

namespace App\Domain\Documents\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'name',
        'content',
        'is_direct_download',
        'created_by',
        'document_category_id',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class);
    }

    protected function casts(): array
    {
        return [
            'is_direct_download' => 'boolean',
        ];
    }
}
