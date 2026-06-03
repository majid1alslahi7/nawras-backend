<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'sometimes|required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'doctor_id' => 'sometimes|required|exists:users,id',
            'visit_date' => 'nullable|date',
            'chief_complaint' => 'sometimes|required|string|max:1000',
            'present_illness' => 'nullable|string|max:1000',
            'diagnosis_initial' => 'nullable|string|max:1000',
            'diagnosis_final' => 'nullable|string|max:1000',
            'icd10_code' => 'nullable|string|max:20',
            'doctor_notes' => 'nullable|string|max:2000',
            'plan' => 'nullable|string|max:2000',
            'follow_up_date' => 'nullable|date',
            'status' => 'nullable|in:قيد الكشف,فحوصات مطلوبة,في انتظار النتائج,نتائج جاهزة,مكتمل',
            'is_free' => 'nullable|boolean',
            'vitals' => 'nullable|array',
            'vitals.blood_pressure_sys' => 'nullable|integer|min:60|max:250',
            'vitals.blood_pressure_dia' => 'nullable|integer|min:30|max:150',
            'vitals.heart_rate' => 'nullable|integer|min:30|max:250',
            'vitals.temperature' => 'nullable|numeric|min:30|max:45',
            'vitals.oxygen_saturation' => 'nullable|integer|min:50|max:100',
            'vitals.blood_sugar' => 'nullable|numeric|min:20|max:600',
            'vitals.weight_kg' => 'nullable|numeric|min:1|max:300',
            'vitals.height_cm' => 'nullable|numeric|min:30|max:250',
            'vitals.pain_level' => 'nullable|integer|min:0|max:10',
        ];
    }
}
