<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\VisitController;
use App\Http\Controllers\Api\LabRequestController;
use App\Http\Controllers\Api\LabResultController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TransactionCategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SystemSettingController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\MedicationController;
use App\Http\Controllers\Api\LabTestController;

// المصادقة
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // لوحة التحكم
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // المرضى
    Route::get('/patients/dropdown', [PatientController::class, 'getDropdown']);
    Route::get('/patients/dropdown/{patient}', [PatientController::class, 'dropdownOption']);
    Route::get('/patients/search', [PatientController::class, 'search']);
    Route::apiResource('patients', PatientController::class);

    // الأطباء والقوائم الطبية
    Route::get('/doctors/dropdown', [UserController::class, 'doctorsDropdown']);
    Route::get('/doctors/dropdown/{doctor}', [UserController::class, 'doctorOption']);
    Route::get('/medications/dropdown', [MedicationController::class, 'dropdown']);
    Route::apiResource('medications', MedicationController::class);
    Route::get('/lab-tests/dropdown', [LabTestController::class, 'dropdown']);
    Route::apiResource('lab-tests', LabTestController::class);

    // المواعيد
    Route::get('/appointments/today', [AppointmentController::class, 'today']);
    Route::get('/appointments/unpaid', [AppointmentController::class, 'unpaid']);
    Route::get('/appointments/doctor-view', [AppointmentController::class, 'doctorView']);
    Route::get('/appointments/paid-patients', [AppointmentController::class, 'paidPatients']);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    Route::apiResource('appointments', AppointmentController::class);

    // الزيارات
    Route::apiResource('visits', VisitController::class);
    Route::post('/visits/{visit}/vitals', [VisitController::class, 'storeVitals']);
    Route::post('/visits/{visit}/complete', [VisitController::class, 'complete']);

    // الفحوصات
    Route::get('/lab-requests/{labRequest}/pdf', [LabRequestController::class, 'downloadPdf']);
    Route::apiResource('lab-requests', LabRequestController::class);
    Route::patch('/lab-requests/{labRequest}/status', [LabRequestController::class, 'updateStatus']);

    // نتائج الفحوصات
    Route::get('/lab-results/unreviewed', [LabResultController::class, 'unreviewed']);
    Route::post('/lab-results/{labResult}/review', [LabResultController::class, 'review']);
    Route::apiResource('lab-results', LabResultController::class);

    // الوصفات
    Route::get('/prescriptions/{prescription}/pdf', [PrescriptionController::class, 'downloadPdf']);
    Route::apiResource('prescriptions', PrescriptionController::class);
    Route::post('/prescriptions/{prescription}/print', [PrescriptionController::class, 'markPrinted']);

    // المعاملات المالية
    Route::get('/categories', [TransactionCategoryController::class, 'index']);
    Route::get('/receipts/{type}', [TransactionController::class, 'receipts'])->whereIn('type', ['income', 'expense']);
    Route::get('/transactions/summary/daily', [TransactionController::class, 'dailySummary']);
    Route::get('/transactions/summary/monthly', [TransactionController::class, 'monthlySummary']);
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receiptPdf']);
    Route::apiResource('transactions', TransactionController::class);

    // التقارير
    Route::get('/reports/financial', [ReportController::class, 'financial']);
    Route::get('/reports/patient-stats', [ReportController::class, 'patientStats']);
    Route::get('/reports/doctor-stats', [ReportController::class, 'doctorStats']);
    Route::get('/reports/patients', [ReportController::class, 'patients']);
    Route::get('/reports/visits', [ReportController::class, 'visits']);
    Route::get('/reports/{report}/export', [ReportController::class, 'export'])
        ->whereIn('report', ['financial', 'patient-stats', 'doctor-stats', 'patients', 'visits', 'all']);

    // المستخدمون
    Route::apiResource('users', UserController::class)->except(['show']);

    // الإعدادات
    Route::get('/settings', [SystemSettingController::class, 'index']);
    Route::put('/settings', [SystemSettingController::class, 'update']);

    // سجل النشاطات
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
});
