<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'doctor' => new UserResource($this->whenLoaded('doctor')),
            'appointment_id' => $this->appointment_id,
            'visit_date' => $this->visit_date?->format('Y-m-d H:i'),
            'chief_complaint' => $this->chief_complaint,
            'present_illness' => $this->present_illness,
            'diagnosis_initial' => $this->diagnosis_initial,
            'diagnosis_final' => $this->diagnosis_final,
            'icd10_code' => $this->icd10_code,
            'doctor_notes' => $this->doctor_notes,
            'plan' => $this->plan,
            'follow_up_date' => $this->follow_up_date?->format('Y-m-d'),
            'status' => $this->status,
            'is_free' => $this->is_free,
            'vitals' => new VisitVitalResource($this->whenLoaded('vitals')),
            'lab_requests' => LabRequestResource::collection($this->whenLoaded('labRequests')),
            'prescriptions' => PrescriptionResource::collection($this->whenLoaded('prescriptions')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
