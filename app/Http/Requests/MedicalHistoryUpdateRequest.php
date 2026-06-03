<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalHistoryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'chronic_diseases' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:2000',
            'previous_surgeries' => 'nullable|string|max:2000',
            'current_medications' => 'nullable|string|max:2000',
            'family_history' => 'nullable|string|max:2000',
            'smoking_status' => 'nullable|in:غير مدخن,مدخن,مدخن سابق',
            'alcohol_status' => 'nullable|in:لا يشرب,يشرب,سابق',
            'pregnancy_status' => 'nullable|boolean',
            'last_menstrual_date' => 'nullable|date',
            'height_cm' => 'nullable|numeric|min:30|max:250',
            'weight_kg' => 'nullable|numeric|min:1|max:300',
            'bmi' => 'nullable|numeric|min:5|max:80',
        ];
    }
}
