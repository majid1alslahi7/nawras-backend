<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $fillable = [
        'category_id', 'visit_id', 'patient_id', 'appointment_id', 'entered_by',
        'transaction_date', 'type', 'amount', 'discount', 'tax',
        'total_amount', 'payment_method', 'description', 'receipt_number', 'receipt_type',
        'receipt_image_path', 'is_reconciled', 'reconciled_at', 'notes',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_reconciled' => 'boolean',
        'reconciled_at' => 'datetime',
    ];

    public function category(): BelongsTo { return $this->belongsTo(TransactionCategory::class); }
    public function visit(): BelongsTo { return $this->belongsTo(Visit::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function enteredBy(): BelongsTo { return $this->belongsTo(User::class, 'entered_by'); }

    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction) {
            if (blank($transaction->receipt_number)) {
                $prefix = $transaction->type === 'إيراد' ? 'REC' : 'PAY';
                $transaction->receipt_number = $prefix . '-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
            }
        });
    }
}
