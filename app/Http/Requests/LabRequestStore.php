<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabRequestStore extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'visit_id' => 'nullable|exists:visits,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'tests_list_json' => 'required|array|min:1',
            'tests_list_json.*.test_name' => 'required|string|max:200',
            'tests_list_json.*.category' => 'nullable|string|max:50',
            'clinical_diagnosis' => 'nullable|string|max:500',
            'urgency' => 'nullable|in:عادي,عاجل,طارئ',
            'notes' => 'nullable|string|max:500',
            'expected_result_date' => 'nullable|date',
        ];
    }
}
