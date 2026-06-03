<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Prescription extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'doctor_id', 'prescription_date',
        'prescription_number', 'diagnosis', 'notes', 'is_printed', 'print_count',
    ];

    protected $casts = [
        'prescription_date' => 'datetime',
        'is_printed' => 'boolean',
    ];

    public function visit(): BelongsTo { return $this->belongsTo(Visit::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(User::class, 'doctor_id'); }
    public function items(): HasMany { return $this->hasMany(PrescriptionItem::class)->orderBy('order_number'); }

    protected static function booted(): void
    {
        static::creating(function (Prescription $prescription) {
            if (blank($prescription->prescription_number)) {
                $prescription->prescription_number = 'RX-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
            }
        });
    }
}
