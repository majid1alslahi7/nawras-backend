<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'sometimes|required|exists:patients,id',
            'appointment_date' => 'sometimes|required|date',
            'appointment_time' => 'sometimes|required|date_format:H:i',
            'visit_reason' => 'sometimes|required|string|max:255',
            'visit_type' => 'sometimes|required|in:كشف جديد,متابعة,عرض نتائج,استشارة,طارئ,إجراء',
            'status' => 'nullable|in:مؤكد,قيد الانتظار,حضر,جاري الكشف,مكتمل,ملغى,لم يحضر',
            'priority' => 'nullable|in:عادي,عاجل,طارئ',
            'notes' => 'nullable|string|max:500',
            'is_free' => 'nullable|boolean',
            'free_until' => 'nullable|date',
            'payment_notes' => 'nullable|string|max:1000',
        ];
    }
}
