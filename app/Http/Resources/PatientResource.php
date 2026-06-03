<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_number' => $this->file_number,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'phone2' => $this->phone2,
            'address' => $this->address,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'age' => $this->age,
            'gender' => $this->gender,
            'blood_type' => $this->blood_type,
            'national_id' => $this->national_id,
            'email' => $this->email,
            'occupation' => $this->occupation,
            'marital_status' => $this->marital_status,
            'emergency_contact' => [
                'name' => $this->emergency_contact_name,
                'phone' => $this->emergency_contact_phone,
            ],
            'notes' => $this->notes,
            'medical_history' => new MedicalHistoryResource($this->whenLoaded('medicalHistory')),
            'total_visits' => $this->when($this->total_visits !== null, $this->total_visits),
            'last_visit' => $this->when($this->last_visit, [
                'date' => $this->last_visit?->visit_date?->format('Y-m-d'),
                'diagnosis' => $this->last_visit?->diagnosis_final,
                'status' => $this->last_visit?->status,
            ]),
            'recent_visits' => VisitResource::collection($this->whenLoaded('visits')),
            'upcoming_appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
