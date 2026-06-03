<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'role' => $this->role,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'specialty' => $this->when($this->relationLoaded('doctor'), $this->doctor?->specialty),
            'position' => $this->when($this->relationLoaded('nurse'), $this->nurse?->position),
        ];
    }
}
