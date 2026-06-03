<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'visit_reason' => 'required|string|max:255',
            'visit_type' => 'required|in:كشف جديد,متابعة,عرض نتائج,استشارة,طارئ,إجراء',
            'priority' => 'nullable|in:عادي,عاجل,طارئ',
            'notes' => 'nullable|string|max:500',
            'created_by' => 'required|exists:users,id',
            'is_free' => 'nullable|boolean',
            'free_until' => 'nullable|date',
            'payment_notes' => 'nullable|string|max:1000',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['created_by' => auth()->id()]);
    }
}
