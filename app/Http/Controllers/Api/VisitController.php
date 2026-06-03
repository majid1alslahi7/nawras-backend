<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VisitRequest;
use App\Http\Requests\VisitUpdateRequest;
use App\Http\Requests\VisitVitalStoreRequest;
use App\Http\Resources\VisitResource;
use App\Models\Visit;
use App\Models\VisitVital;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Visit::with(['patient', 'doctor', 'vitals']);

        if ($request->filled('patient_id')) $query->byPatient($request->patient_id);
        if ($request->filled('doctor_id')) $query->byDoctor($request->doctor_id);
        if ($request->filled('status')) $query->byStatus($request->status);
        if ($request->get('filter') === 'today') $query->today();
        if ($request->get('filter') === 'pending') $query->pendingResults();
        if ($request->get('filter') === 'completed') $query->completed();
        if ($request->filled('date_from') && $request->filled('date_to')) $query->byDateRange($request->date_from, $request->date_to);
        if ($request->filled('search')) $query->search($request->search);

        $visits = $query->recent()->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => VisitResource::collection($visits),
            'meta' => ['total' => $visits->total(), 'page' => $visits->currentPage(), 'last_page' => $visits->lastPage()],
        ]);
    }

    public function store(VisitRequest $request): JsonResponse
    {
        $visit = Visit::create($request->validated());

        // تحديث حالة الموعد إذا وجد
        if ($visit->appointment_id) {
            Appointment::where('id', $visit->appointment_id)->update(['status' => 'جاري الكشف']);
        }

        // حفظ العلامات الحيوية
        if ($request->filled('vitals')) {
            $visit->vitals()->create($request->vitals + ['measured_by' => auth()->id()]);
        }

        return response()->json([
            'message' => 'تم بدء الكشف بنجاح',
            'data' => new VisitResource($visit->load(['patient', 'doctor', 'vitals'])),
        ], 201);
    }

    public function show(Visit $visit): JsonResponse
    {
        $visit->load(['patient.medicalHistory', 'doctor', 'vitals', 'labRequests.results', 'prescriptions.items', 'transactions']);
        return response()->json(new VisitResource($visit));
    }

    public function update(VisitUpdateRequest $request, Visit $visit): JsonResponse
    {
        $data = $request->validated();
        $vitals = $data['vitals'] ?? null;
        unset($data['vitals']);

        $visit->update($data);

        if ($vitals !== null) {
            $visit->vitals()->updateOrCreate(
                ['visit_id' => $visit->id],
                $vitals + ['measured_by' => auth()->id()]
            );
        }

        return response()->json(['message' => 'تم تحديث الكشف', 'data' => new VisitResource($visit->load('patient', 'doctor', 'vitals'))]);
    }

    public function destroy(Visit $visit): JsonResponse
    {
        $visit->delete();

        return response()->json(['message' => 'تم حذف الكشف']);
    }

    public function storeVitals(VisitVitalStoreRequest $request, Visit $visit): JsonResponse
    {
        $vitals = $visit->vitals()->updateOrCreate(
            ['visit_id' => $visit->id],
            $request->validated() + ['measured_by' => auth()->id()]
        );
        return response()->json(['message' => 'تم حفظ العلامات الحيوية', 'data' => $vitals], 201);
    }

    public function complete(Request $request, Visit $visit): JsonResponse
    {
        $visit->update([
            'status' => 'مكتمل',
            'diagnosis_final' => $request->diagnosis_final ?? $visit->diagnosis_initial,
        ]);

        if ($visit->appointment_id) {
            Appointment::where('id', $visit->appointment_id)->update(['status' => 'مكتمل']);
        }

        return response()->json(['message' => 'تم إكمال الكشف بنجاح']);
    }
}
