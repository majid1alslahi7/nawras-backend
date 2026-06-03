<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LabRequest extends Model
{
    protected $fillable = [
        'visit_id', 'patient_id', 'doctor_id', 'request_date', 'request_number',
        'tests_list_json', 'clinical_diagnosis', 'urgency', 'notes', 'status', 'expected_result_date',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'tests_list_json' => 'array',
        'expected_result_date' => 'date',
    ];

    public function visit(): BelongsTo { return $this->belongsTo(Visit::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(User::class, 'doctor_id'); }
    public function results(): HasMany { return $this->hasMany(LabResult::class); }

    protected static function booted(): void
    {
        static::creating(function (LabRequest $labRequest) {
            if (blank($labRequest->request_number)) {
                $labRequest->request_number = 'LAB-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
            }
        });
    }
}
