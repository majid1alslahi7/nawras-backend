<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'appointment_date' => $this->appointment_date?->format('Y-m-d'),
            'appointment_time' => $this->appointment_time,
            'visit_reason' => $this->visit_reason,
            'visit_type' => $this->visit_type,
            'status' => $this->status,
            'status_color' => $this->status_color,
            'priority' => $this->priority,
            'notes' => $this->notes,
            'is_paid' => $this->is_paid,
            'is_free' => $this->is_free,
            'has_valid_receipt' => $this->has_valid_receipt,
            'paid_at' => $this->paid_at?->format('Y-m-d H:i'),
            'free_until' => $this->free_until?->format('Y-m-d'),
            'payment_status' => $this->payment_status,
            'paid_transaction_id' => $this->paid_transaction_id,
            'paid_transaction' => new TransactionResource($this->whenLoaded('paidTransaction')),
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
