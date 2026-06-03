<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionItemUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'order_number' => 'nullable|integer|min:1',
            'medication_name' => 'sometimes|required|string|max:200',
            'concentration' => 'nullable|string|max:100',
            'dosage' => 'sometimes|required|string|max:100',
            'frequency' => 'sometimes|required|string|max:100',
            'duration' => 'nullable|string|max:100',
            'quantity' => 'nullable|string|max:50',
            'route' => 'nullable|in:فموي,موضعي,حقن,وريدي,عضلي,تحت الجلد,شرجي,استنشاق,عين,أذن',
            'timing' => 'nullable|in:قبل الأكل,بعد الأكل,مع الأكل,عند النوم,عند اللزوم,غير محدد',
            'instructions' => 'nullable|string|max:255',
        ];
    }
}
