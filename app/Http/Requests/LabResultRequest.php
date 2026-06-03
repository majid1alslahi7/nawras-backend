<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabResultRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'lab_request_id' => 'required|exists:lab_requests,id',
            'visit_id' => 'nullable|exists:visits,id',
            'patient_id' => 'nullable|exists:patients,id',
            'lab_name' => 'nullable|string|max:150',
            'lab_reference' => 'nullable|string|max:50',
            'results_json' => 'required|array|min:1',
            'results_json.*.test_name' => 'required|string',
            'results_json.*.result' => 'required|string',
            'results_json.*.unit' => 'nullable|string',
            'results_json.*.normal_range' => 'nullable|string',
            'results_json.*.is_abnormal' => 'nullable|boolean',
            'report_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
