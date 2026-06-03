<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id', 'order_number', 'medication_name', 'concentration',
        'dosage', 'frequency', 'duration', 'quantity', 'route', 'timing', 'instructions',
    ];

    public function prescription(): BelongsTo { return $this->belongsTo(Prescription::class); }
}
