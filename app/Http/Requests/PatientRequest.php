<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:patients,phone',
            'phone2' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:ذكر,أنثى',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'national_id' => 'nullable|string|max:20|unique:patients,national_id',
            'email' => 'nullable|email|max:100',
            'occupation' => 'nullable|string|max:100',
            'marital_status' => 'nullable|in:أعزب,متزوج,مطلق,أرمل',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'medical_history' => 'nullable|array',
            'medical_history.chronic_diseases' => 'nullable|string',
            'medical_history.allergies' => 'nullable|string',
            'medical_history.previous_surgeries' => 'nullable|string',
            'medical_history.current_medications' => 'nullable|string',
            'medical_history.smoking_status' => 'nullable|in:غير مدخن,مدخن,مدخن سابق',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف مسجل مسبقاً',
            'birth_date.before' => 'تاريخ الميلاد يجب أن يكون قبل اليوم',
        ];
    }
}
