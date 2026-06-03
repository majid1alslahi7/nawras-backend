<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name_ar,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'type' => $this->type,
            'icon' => $this->icon,
            'color' => $this->color,
        ];
    }
}
