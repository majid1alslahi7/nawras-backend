<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lab_request_id' => $this->lab_request_id,
            'visit_id' => $this->visit_id,
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'lab_request' => new LabRequestResource($this->whenLoaded('labRequest')),
            'lab_name' => $this->lab_name,
            'lab_reference' => $this->lab_reference,
            'result_date' => $this->result_date?->format('Y-m-d H:i'),
            'results' => $this->results_json,
            'report_image_url' => $this->report_image_path ? asset('storage/' . $this->report_image_path) : null,
            'is_abnormal' => $this->is_abnormal,
            'doctor_reviewed' => $this->doctor_reviewed,
            'doctor_reviewed_at' => $this->doctor_reviewed_at?->format('Y-m-d H:i'),
            'entered_by' => new UserResource($this->whenLoaded('enteredBy')),
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
