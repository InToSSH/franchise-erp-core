<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'email',
        'phone',
        'street',
        'city',
        'post_code',
        'country',
        'cin',
        'tin',
        'bank_account',
    ];
}
