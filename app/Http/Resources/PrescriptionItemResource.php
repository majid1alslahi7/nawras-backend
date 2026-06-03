<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order_number' => $this->order_number,
            'medication_name' => $this->medication_name,
            'concentration' => $this->concentration,
            'dosage' => $this->dosage,
            'frequency' => $this->frequency,
            'duration' => $this->duration,
            'quantity' => $this->quantity,
            'route' => $this->route,
            'timing' => $this->timing,
            'instructions' => $this->instructions,
            'full_text' => "{$this->medication_name} {$this->concentration} - {$this->dosage} {$this->frequency} - {$this->duration}",
        ];
    }
}
