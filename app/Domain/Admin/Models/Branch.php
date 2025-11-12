<?php

declare(strict_types=1);

namespace App\Domain\Admin\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'code',
        'manager_id',
        'email',
        'phone',
        'cin',
        'tin',
        'street',
        'city',
        'post_code',
        'country',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'branch_user');
    }

    public function fullAddress(): Attribute
    {
        return Attribute::make(
            get: fn () => trim("{$this->street}, {$this->post_code} {$this->city}, {$this->country}", ', '),
        );
    }
}
