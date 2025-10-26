<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->street}, {$this->post_code} {$this->city}, {$this->country}", ', '),
        );
    }
}
