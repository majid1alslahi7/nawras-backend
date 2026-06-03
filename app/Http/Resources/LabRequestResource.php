<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'request_number' => $this->request_number,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'doctor' => new UserResource($this->whenLoaded('doctor')),
            'visit_id' => $this->visit_id,
            'request_date' => $this->request_date?->format('Y-m-d H:i'),
            'tests_list' => $this->tests_list_json,
            'clinical_diagnosis' => $this->clinical_diagnosis,
            'urgency' => $this->urgency,
            'notes' => $this->notes,
            'status' => $this->status,
            'expected_result_date' => $this->expected_result_date?->format('Y-m-d'),
            'results' => LabResultResource::collection($this->whenLoaded('results')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
