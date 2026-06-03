<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabRequestUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'visit_id' => 'nullable|exists:visits,id',
            'patient_id' => 'sometimes|required|exists:patients,id',
            'doctor_id' => 'sometimes|required|exists:users,id',
            'tests_list_json' => 'sometimes|required|array|min:1',
            'tests_list_json.*.test_name' => 'required_with:tests_list_json|string|max:200',
            'tests_list_json.*.category' => 'nullable|string|max:50',
            'clinical_diagnosis' => 'nullable|string|max:500',
            'urgency' => 'nullable|in:عادي,عاجل,طارئ',
            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|in:مطلوب,تم السحب,في المختبر,نتائج جاهزة,تم التسليم,منتهي,ملغى',
            'expected_result_date' => 'nullable|date',
        ];
    }
}
