<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Visit;
use App\Models\LabResult;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $today = today();

        $data = [
            'user' => [
                'name' => $user->full_name,
                'role' => $user->role,
            ],
            'today_stats' => [
                'appointments' => Appointment::today()->count(),
                'pending_appointments' => Appointment::today()->pending()->count(),
                'visits' => Visit::today()->count(),
                'completed_visits' => Visit::today()->completed()->count(),
                'pending_lab_results' => LabResult::where('doctor_reviewed', false)->count(),
                'unreviewed_abnormal_results' => LabResult::where('doctor_reviewed', false)->where('is_abnormal', true)->count(),
            ],
            'financial_today' => Transaction::whereDate('transaction_date', $today)
                ->selectRaw("SUM(CASE WHEN type = 'إيراد' THEN total_amount ELSE 0 END) as income,
                             SUM(CASE WHEN type = 'مصروف' THEN total_amount ELSE 0 END) as expense")
                ->first(),
        ];

        if ($user->isDoctor()) {
            $data['today_appointments'] = Appointment::with('patient:id,full_name,phone,file_number')
                ->today()->orderBy('appointment_time')->get()
                ->map(fn($a) => [
                    'id' => $a->id,
                    'time' => $a->appointment_time,
                    'patient_name' => $a->patient->full_name,
                    'reason' => $a->visit_reason,
                    'status' => $a->status,
                    'status_color' => $a->status_color,
                ]);
        }

        return response()->json($data);
    }

    public function stats(): JsonResponse
    {
        $thisMonth = now()->startOfMonth();

        return response()->json([
            'total_patients' => Patient::count(),
            'new_patients_this_month' => Patient::where('created_at', '>=', $thisMonth)->count(),
            'total_visits_this_month' => Visit::where('visit_date', '>=', $thisMonth)->count(),
            'total_lab_requests_this_month' => \App\Models\LabRequest::where('request_date', '>=', $thisMonth)->count(),
            'total_prescriptions_this_month' => \App\Models\Prescription::where('prescription_date', '>=', $thisMonth)->count(),
            'revenue_this_month' => Transaction::where('type', 'إيراد')->where('transaction_date', '>=', $thisMonth)->sum('total_amount'),
            'expense_this_month' => Transaction::where('type', 'مصروف')->where('transaction_date', '>=', $thisMonth)->sum('total_amount'),
        ]);
    }
}
