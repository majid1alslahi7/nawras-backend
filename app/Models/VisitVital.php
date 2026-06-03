<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitVital extends Model
{
    protected $fillable = [
        'visit_id', 'blood_pressure_sys', 'blood_pressure_dia', 'heart_rate',
        'temperature', 'respiratory_rate', 'oxygen_saturation', 'blood_sugar',
        'weight_kg', 'height_cm', 'bmi', 'pain_level', 'notes', 'measured_by',
    ];

    public function visit(): BelongsTo { return $this->belongsTo(Visit::class); }
    public function measurer(): BelongsTo { return $this->belongsTo(User::class, 'measured_by'); }
}
