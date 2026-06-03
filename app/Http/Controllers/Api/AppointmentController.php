<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Appointment::with(['patient', 'creator', 'paidTransaction']);

        if ($request->filled('date')) {
            $query->byDate($request->date);
        } elseif ($request->get('filter') === 'today') {
            $query->today();
        } elseif ($request->get('filter') === 'week') {
            $query->thisWeek();
        } elseif ($request->get('filter') === 'upcoming') {
            $query->upcoming();
        }

        if ($request->filled('status')) $query->byStatus($request->status);
        if ($request->filled('patient_id')) $query->byPatient($request->patient_id);
        if ($request->filled('search')) $query->search($request->search);

        $appointments = $query->orderBy('appointment_date')->orderBy('appointment_time')->paginate($request->get('per_page', 30));

        return response()->json([
            'data' => AppointmentResource::collection($appointments),
            'meta' => ['total' => $appointments->total(), 'page' => $appointments->currentPage(), 'last_page' => $appointments->lastPage()],
        ]);
    }

    public function unpaid(Request $request): JsonResponse
    {
        $appointments = Appointment::with(['patient', 'creator'])
            ->unpaid()
            ->when($request->filled('search'), fn ($query) => $query->whereHas('patient', fn ($q) => $q->search($request->search)))
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'data' => AppointmentResource::collection($appointments),
            'meta' => ['total' => $appointments->total(), 'page' => $appointments->currentPage(), 'last_page' => $appointments->lastPage()],
        ]);
    }

    public function doctorView(Request $request): JsonResponse
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $appointments = Appointment::with(['patient', 'paidTransaction'])
            ->whereDate('appointment_date', $date)
            ->paidOrFree()
            ->orderBy('appointment_time')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'data' => AppointmentResource::collection($appointments),
            'meta' => ['total' => $appointments->total(), 'page' => $appointments->currentPage(), 'last_page' => $appointments->lastPage()],
        ]);
    }

    public function paidPatients(Request $request): JsonResponse
    {
        $appointments = Appointment::with('patient')
            ->paidOrFree()
            ->when($request->filled('search'), fn ($query) => $query->whereHas('patient', fn ($q) => $q->search($request->search)))
            ->whereDate('appointment_date', '>=', now()->subDays(14))
            ->latest('appointment_date')
            ->limit(50)
            ->get()
            ->map(fn (Appointment $appointment) => [
                'id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'full_name' => $appointment->patient?->full_name,
                'phone' => $appointment->patient?->phone,
                'appointment_date' => $appointment->appointment_date?->format('Y-m-d'),
                'visit_reason' => $appointment->visit_reason,
                'has_valid_receipt' => $appointment->has_valid_receipt,
                'free_until' => $appointment->free_until?->format('Y-m-d'),
            ]);

        return response()->json(['data' => $appointments]);
    }

    public function today(): JsonResponse
    {
        $appointments = Appointment::with('patient')->today()->orderBy('appointment_time')->get();
        return response()->json(AppointmentResource::collection($appointments));
    }

    public function store(AppointmentRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['payment_status'] = ($data['is_free'] ?? false) ? 'free' : 'unpaid';
        $appointment = Appointment::create($data);
        return response()->json(['message' => 'تم حجز الموعد بنجاح', 'data' => new AppointmentResource($appointment->load('patient'))], 201);
    }

    public function show(Appointment $appointment): JsonResponse
    {
        return response()->json(new AppointmentResource($appointment->load(['patient', 'creator', 'visit', 'paidTransaction'])));
    }

    public function update(AppointmentUpdateRequest $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validated();
        if (array_key_exists('is_free', $data) && $data['is_free']) {
            $data['payment_status'] = 'free';
        } elseif (array_key_exists('is_free', $data) && !$data['is_free'] && !$appointment->paid_transaction_id) {
            $data['payment_status'] = 'unpaid';
        }
        $appointment->update($data);
        return response()->json(['message' => 'تم تحديث الموعد', 'data' => new AppointmentResource($appointment->load('patient'))]);
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $appointment->delete();

        return response()->json(['message' => 'تم حذف الموعد']);
    }

    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $request->validate(['status' => 'required|in:مؤكد,قيد الانتظار,حضر,جاري الكشف,مكتمل,ملغى,لم يحضر']);
        $oldStatus = $appointment->status;
        $appointment->update([
            'status' => $request->status,
            'cancelled_by' => $request->status === 'ملغى' ? auth()->id() : null,
            'cancellation_reason' => $request->status === 'ملغى' ? $request->cancellation_reason : null,
        ]);
        return response()->json(['message' => "تم تغيير الحالة من {$oldStatus} إلى {$request->status}"]);
    }

    public function markPaid(Appointment $appointment, Transaction $transaction): void
    {
        $appointment->update([
            'paid_transaction_id' => $transaction->id,
            'paid_at' => now(),
            'payment_status' => 'paid',
            'payment_notes' => $transaction->description,
        ]);
    }
}
