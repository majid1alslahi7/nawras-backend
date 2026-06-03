<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalHistory extends Model
{
    protected $fillable = [
        'patient_id', 'chronic_diseases', 'allergies', 'previous_surgeries',
        'current_medications', 'family_history', 'smoking_status', 'alcohol_status',
        'pregnancy_status', 'last_menstrual_date', 'height_cm', 'weight_kg', 'bmi', 'updated_by',
    ];

    public $timestamps = false;

    protected $casts = [
        'pregnancy_status' => 'boolean',
        'last_menstrual_date' => 'date',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
}
