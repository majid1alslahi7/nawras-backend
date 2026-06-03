<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'category' => new TransactionCategoryResource($this->whenLoaded('category')),
            'visit_id' => $this->visit_id,
            'appointment_id' => $this->appointment_id,
            'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'entered_by' => new UserResource($this->whenLoaded('enteredBy')),
            'transaction_date' => $this->transaction_date?->format('Y-m-d H:i'),
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'total_amount' => (float) $this->total_amount,
            'payment_method' => $this->payment_method,
            'description' => $this->description,
            'receipt_number' => $this->receipt_number,
            'receipt_type' => $this->receipt_type,
            'receipt_image_url' => $this->receipt_image_path ? asset('storage/' . $this->receipt_image_path) : null,
            'is_reconciled' => $this->is_reconciled,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
