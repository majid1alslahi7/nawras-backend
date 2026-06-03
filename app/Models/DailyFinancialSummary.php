<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyFinancialSummary extends Model
{
    protected $fillable = [
        'summary_date', 'total_income', 'total_expense', 'net_profit',
        'cash_income', 'card_income', 'transfer_income',
        'patient_count', 'visit_count', 'prescription_count', 'lab_request_count', 'notes',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'total_income' => 'decimal:2',
        'total_expense' => 'decimal:2',
        'net_profit' => 'decimal:2',
    ];
}
