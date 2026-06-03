<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitVitalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'blood_pressure' => $this->blood_pressure_sys && $this->blood_pressure_dia
                ? "{$this->blood_pressure_sys}/{$this->blood_pressure_dia}"
                : null,
            'blood_pressure_sys' => $this->blood_pressure_sys,
            'blood_pressure_dia' => $this->blood_pressure_dia,
            'heart_rate' => $this->heart_rate,
            'temperature' => $this->temperature,
            'respiratory_rate' => $this->respiratory_rate,
            'oxygen_saturation' => $this->oxygen_saturation,
            'blood_sugar' => $this->blood_sugar,
            'weight_kg' => $this->weight_kg,
            'height_cm' => $this->height_cm,
            'bmi' => $this->bmi,
            'pain_level' => $this->pain_level,
            'notes' => $this->notes,
        ];
    }
}
