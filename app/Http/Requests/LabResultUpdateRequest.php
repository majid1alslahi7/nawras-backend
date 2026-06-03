<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabResultUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'lab_request_id' => 'sometimes|required|exists:lab_requests,id',
            'visit_id' => 'nullable|exists:visits,id',
            'patient_id' => 'nullable|exists:patients,id',
            'lab_name' => 'nullable|string|max:150',
            'lab_reference' => 'nullable|string|max:50',
            'results_json' => 'sometimes|required|array|min:1',
            'results_json.*.test_name' => 'required_with:results_json|string',
            'results_json.*.result' => 'required_with:results_json|string',
            'results_json.*.unit' => 'nullable|string',
            'results_json.*.normal_range' => 'nullable|string',
            'results_json.*.is_abnormal' => 'nullable|boolean',
            'report_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'doctor_reviewed' => 'nullable|boolean',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
