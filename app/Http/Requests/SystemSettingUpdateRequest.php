<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemSettingUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'clinic_name' => 'nullable|string|max:150',
            'clinic_phone' => 'nullable|string|max:30',
            'clinic_address' => 'nullable|string|max:500',
            'default_visit_price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:20',
        ];
    }
}
