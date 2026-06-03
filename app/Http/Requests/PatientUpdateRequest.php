<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'full_name' => 'sometimes|string|max:100',
            'phone' => 'sometimes|string|max:20|unique:patients,phone,' . $this->route('patient')->id,
            'phone2' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:ذكر,أنثى',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'national_id' => 'nullable|string|max:20|unique:patients,national_id,' . $this->route('patient')->id,
            'email' => 'nullable|email|max:100',
            'occupation' => 'nullable|string|max:100',
            'marital_status' => 'nullable|in:أعزب,متزوج,مطلق,أرمل',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'medical_history' => 'nullable|array',
        ];
    }
}
