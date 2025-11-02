<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Resources;

use App\Domain\Catalog\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Supplier */
class SupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'street' => $this->street,
            'city' => $this->city,
            'post_code' => $this->post_code,
            'country' => $this->country,
            'full_address' => $this->fullAddress,
            'cin' => $this->cin,
            'tin' => $this->tin,
            'bank_account' => $this->bank_account,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
