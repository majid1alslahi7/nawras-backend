<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitVitalStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'blood_pressure_sys' => 'nullable|integer|min:60|max:250',
            'blood_pressure_dia' => 'nullable|integer|min:30|max:150',
            'heart_rate' => 'nullable|integer|min:30|max:250',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'respiratory_rate' => 'nullable|integer|min:5|max:80',
            'oxygen_saturation' => 'nullable|integer|min:50|max:100',
            'blood_sugar' => 'nullable|numeric|min:20|max:600',
            'weight_kg' => 'nullable|numeric|min:1|max:300',
            'height_cm' => 'nullable|numeric|min:30|max:250',
            'bmi' => 'nullable|numeric|min:5|max:80',
            'pain_level' => 'nullable|integer|min:0|max:10',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
