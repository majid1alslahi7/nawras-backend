<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'file_number', 'full_name', 'phone', 'phone2', 'address',
        'birth_date', 'gender', 'blood_type', 'national_id', 'email',
        'occupation', 'marital_status', 'emergency_contact_name',
        'emergency_contact_phone', 'notes',
    ];

    protected $casts = ['birth_date' => 'date'];

    public function medicalHistory(): HasOne { return $this->hasOne(MedicalHistory::class); }
    public function appointments(): HasMany { return $this->hasMany(Appointment::class); }
    public function visits(): HasMany { return $this->hasMany(Visit::class); }
    public function labRequests(): HasMany { return $this->hasMany(LabRequest::class); }
    public function labResults(): HasMany { return $this->hasMany(LabResult::class); }
    public function prescriptions(): HasMany { return $this->hasMany(Prescription::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'LIKE', "%{$term}%")
              ->orWhere('phone', 'LIKE', "%{$term}%")
              ->orWhere('file_number', 'LIKE', "%{$term}%")
              ->orWhere('national_id', 'LIKE', "%{$term}%");
        });
    }

    public function scopeByGender($query, $gender) { return $query->where('gender', $gender); }
    public function scopeByBloodType($query, $type) { return $query->where('blood_type', $type); }
    public function scopeByAgeRange($query, $from, $to)
    {
        return $query->whereBetween('birth_date', [
            now()->subYears((int) $to + 1)->addDay()->toDateString(),
            now()->subYears((int) $from)->toDateString(),
        ]);
    }
    public function scopeByDateRange($query, $from, $to) { return $query->whereBetween('created_at', [$from, $to]); }
    public function scopeRecent($query) { return $query->orderBy('created_at', 'desc'); }

    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getLastVisitAttribute()
    {
        return $this->visits()->latest('visit_date')->first();
    }

    protected static function booted(): void
    {
        static::creating(function ($patient) {
            if (empty($patient->file_number)) {
                $last = static::withTrashed()->latest('id')->first();
                $patient->file_number = 'M-' . str_pad(($last?->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}
