<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'chronic_diseases' => $this->chronic_diseases,
            'allergies' => $this->allergies,
            'previous_surgeries' => $this->previous_surgeries,
            'current_medications' => $this->current_medications,
            'family_history' => $this->family_history,
            'smoking_status' => $this->smoking_status,
            'height_cm' => $this->height_cm,
            'weight_kg' => $this->weight_kg,
            'bmi' => $this->bmi,
            'pregnancy_status' => $this->pregnancy_status,
        ];
    }
}
