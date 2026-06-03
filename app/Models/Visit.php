<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visit extends Model
{
    protected $fillable = [
        'patient_id', 'appointment_id', 'doctor_id', 'visit_date',
        'chief_complaint', 'present_illness', 'diagnosis_initial',
        'diagnosis_final', 'icd10_code', 'doctor_notes', 'plan',
        'follow_up_date', 'status', 'is_free',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'follow_up_date' => 'date',
        'is_free' => 'boolean',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function doctor(): BelongsTo { return $this->belongsTo(User::class, 'doctor_id'); }
    public function vitals(): HasOne { return $this->hasOne(VisitVital::class); }
    public function labRequests(): HasMany { return $this->hasMany(LabRequest::class); }
    public function labResults(): HasMany { return $this->hasMany(LabResult::class); }
    public function prescriptions(): HasMany { return $this->hasMany(Prescription::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }

    public function scopeToday($query) { return $query->whereDate('visit_date', today()); }
    public function scopeCompleted($query) { return $query->where('status', 'مكتمل'); }
    public function scopeByPatient($query, $patientId) { return $query->where('patient_id', $patientId); }
    public function scopeByDoctor($query, $doctorId) { return $query->where('doctor_id', $doctorId); }
    public function scopeByStatus($query, $status) { return $query->where('status', $status); }
    public function scopePendingResults($query) { return $query->whereIn('status', ['فحوصات مطلوبة', 'في انتظار النتائج']); }
    public function scopeByDateRange($query, $from, $to) { return $query->whereBetween('visit_date', [$from, $to]); }
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('chief_complaint', 'LIKE', "%{$term}%")
                ->orWhere('diagnosis_initial', 'LIKE', "%{$term}%")
                ->orWhere('diagnosis_final', 'LIKE', "%{$term}%")
                ->orWhereHas('patient', fn ($patient) => $patient->search($term));
        });
    }
    public function scopeRecent($query) { return $query->orderBy('visit_date', 'desc'); }
}
