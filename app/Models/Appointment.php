<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id', 'appointment_date', 'appointment_time', 'end_time_expected',
        'visit_reason', 'visit_type', 'status', 'priority', 'notes',
        'cancellation_reason', 'is_reminder_sent', 'created_by', 'cancelled_by',
        'paid_transaction_id', 'paid_at', 'is_free', 'free_until', 'payment_status', 'payment_notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'is_reminder_sent' => 'boolean',
        'paid_at' => 'datetime',
        'is_free' => 'boolean',
        'free_until' => 'date',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function canceller(): BelongsTo { return $this->belongsTo(User::class, 'cancelled_by'); }
    public function paidTransaction(): BelongsTo { return $this->belongsTo(Transaction::class, 'paid_transaction_id'); }
    public function visit(): HasOne { return $this->hasOne(Visit::class); }

    public function scopeToday($query) { return $query->whereDate('appointment_date', today()); }
    public function scopePending($query) { return $query->whereIn('status', ['مؤكد', 'قيد الانتظار']); }
    public function scopeUpcoming($query) { return $query->where('appointment_date', '>=', today())->orderBy('appointment_date')->orderBy('appointment_time'); }
    public function scopePaidOrFree($query) { return $query->whereIn('payment_status', ['paid', 'free']); }
    public function scopeUnpaid($query) { return $query->where('payment_status', 'unpaid'); }

    public function getIsPaidAttribute(): bool
    {
        return in_array($this->payment_status, ['paid', 'free'], true) || filled($this->paid_transaction_id);
    }

    public function getHasValidReceiptAttribute(): bool
    {
        return $this->is_paid || ($this->is_free && (!$this->free_until || $this->free_until->isFuture() || $this->free_until->isToday()));
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'مؤكد' => '#2196F3', 'قيد الانتظار' => '#FF9800', 'حضر' => '#4CAF50',
            'جاري الكشف' => '#9C27B0', 'مكتمل' => '#4CAF50', 'ملغى' => '#F44336',
            'لم يحضر' => '#607D8B', default => '#9E9E9E',
        };
    }
}
