<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionItemStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'prescription_id' => 'required|exists:prescriptions,id',
            'order_number' => 'nullable|integer|min:1',
            'medication_name' => 'required|string|max:200',
            'concentration' => 'nullable|string|max:100',
            'dosage' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'duration' => 'nullable|string|max:100',
            'quantity' => 'nullable|string|max:50',
            'route' => 'nullable|in:فموي,موضعي,حقن,وريدي,عضلي,تحت الجلد,شرجي,استنشاق,عين,أذن',
            'timing' => 'nullable|in:قبل الأكل,بعد الأكل,مع الأكل,عند النوم,عند اللزوم,غير محدد',
            'instructions' => 'nullable|string|max:255',
        ];
    }
}
