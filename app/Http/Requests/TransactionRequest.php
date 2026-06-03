<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:transaction_categories,id'],
            'visit_id' => ['nullable', 'exists:visits,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'patient_id' => ['nullable', 'exists:patients,id'],
            'transaction_date' => ['nullable', 'date'],
            'type' => ['required', 'in:إيراد,مصروف'],
            'amount' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'in:نقدي,بطاقة ائتمان,تحويل بنكي,شيك,محفظة إلكترونية'],
            'description' => ['nullable', 'string', 'max:1000'],
            'receipt_number' => ['nullable', 'string', 'max:50', 'unique:transactions,receipt_number'],
            'receipt_type' => ['nullable', 'in:general,appointment_receipt,income_receipt,expense_receipt'],
            'receipt_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
