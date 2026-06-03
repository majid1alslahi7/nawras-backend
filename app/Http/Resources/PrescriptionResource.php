<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'prescription_number' => $this->prescription_number,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'doctor' => new UserResource($this->whenLoaded('doctor')),
            'visit_id' => $this->visit_id,
            'prescription_date' => $this->prescription_date?->format('Y-m-d H:i'),
            'diagnosis' => $this->diagnosis,
            'notes' => $this->notes,
            'is_printed' => $this->is_printed,
            'print_count' => $this->print_count,
            'items' => PrescriptionItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
