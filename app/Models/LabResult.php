<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabResult extends Model
{
    protected $fillable = [
        'lab_request_id', 'visit_id', 'patient_id', 'entered_by',
        'result_date', 'lab_name', 'lab_reference', 'results_json',
        'report_image_path', 'is_abnormal', 'doctor_reviewed',
        'doctor_reviewed_at', 'doctor_reviewed_by', 'notes',
    ];

    protected $casts = [
        'result_date' => 'datetime',
        'results_json' => 'array',
        'is_abnormal' => 'boolean',
        'doctor_reviewed' => 'boolean',
        'doctor_reviewed_at' => 'datetime',
    ];

    public function labRequest(): BelongsTo { return $this->belongsTo(LabRequest::class); }
    public function visit(): BelongsTo { return $this->belongsTo(Visit::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function enteredBy(): BelongsTo { return $this->belongsTo(User::class, 'entered_by'); }
    public function reviewedBy(): BelongsTo { return $this->belongsTo(User::class, 'doctor_reviewed_by'); }
}
