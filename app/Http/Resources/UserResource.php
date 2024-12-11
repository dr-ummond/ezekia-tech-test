<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin  User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'bio' => $this->bio,
            'hourly_rate' => $this->converted_rate,
            'currency' => $this->converted_currency,
            'is_converted' => $this->is_converted,
        ];
    }
}
