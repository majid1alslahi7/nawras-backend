<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionUpdateRequest extends FormRequest
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
            'diagnosis' => 'sometimes|required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'items' => 'nullable|array|min:1',
            'items.*.medication_name' => 'required_with:items|string|max:200',
            'items.*.concentration' => 'nullable|string|max:100',
            'items.*.dosage' => 'required_with:items|string|max:100',
            'items.*.frequency' => 'required_with:items|string|max:100',
            'items.*.duration' => 'nullable|string|max:100',
            'items.*.quantity' => 'nullable|string|max:50',
            'items.*.route' => 'nullable|in:فموي,موضعي,حقن,وريدي,عضلي,تحت الجلد,شرجي,استنشاق,عين,أذن',
            'items.*.timing' => 'nullable|in:قبل الأكل,بعد الأكل,مع الأكل,عند النوم,عند اللزوم,غير محدد',
            'items.*.instructions' => 'nullable|string|max:255',
        ];
    }
}
