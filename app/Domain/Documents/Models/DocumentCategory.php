<?php

declare(strict_types=1);

namespace App\Domain\Documents\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class DocumentCategory extends Model
{
    use NodeTrait;

    protected $fillable = [
        'name',
        'icon'
    ];
}
