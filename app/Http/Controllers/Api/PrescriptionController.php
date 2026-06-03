<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PrescriptionRequest;
use App\Http\Requests\PrescriptionUpdateRequest;
use App\Http\Resources\PrescriptionResource;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Prescription::with(['patient', 'doctor', 'items']);

        if ($request->filled('patient_id')) $query->where('patient_id', $request->patient_id);
        if ($request->filled('visit_id')) $query->where('visit_id', $request->visit_id);
        if ($request->filled('doctor_id')) $query->where('doctor_id', $request->doctor_id);
        if ($request->filled('search')) {
            $query->whereHas('patient', fn($q) => $q->where('full_name', 'LIKE', "%{$request->search}%"));
        }

        $prescriptions = $query->orderBy('prescription_date', 'desc')->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => PrescriptionResource::collection($prescriptions),
            'meta' => ['total' => $prescriptions->total(), 'page' => $prescriptions->currentPage(), 'last_page' => $prescriptions->lastPage()],
        ]);
    }

    public function store(PrescriptionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);

        $prescription = Prescription::create($data);

        // حفظ الأدوية
        foreach ($items as $index => $item) {
            $prescription->items()->create(array_merge($item, ['order_number' => $index + 1]));
        }

        return response()->json([
            'message' => 'تم كتابة الوصفة بنجاح',
            'data' => new PrescriptionResource($prescription->load(['patient', 'doctor', 'items'])),
        ], 201);
    }

    public function show(Prescription $prescription): JsonResponse
    {
        $prescription->load(['patient', 'doctor', 'visit', 'items']);
        return response()->json(new PrescriptionResource($prescription));
    }

    public function update(PrescriptionUpdateRequest $request, Prescription $prescription): JsonResponse
    {
        $data = $request->validated();
        $items = $data['items'] ?? null;
        unset($data['items']);

        $prescription->update($data);

        if ($items !== null) {
            $prescription->items()->delete();
            foreach ($items as $index => $item) {
                $prescription->items()->create(array_merge($item, ['order_number' => $index + 1]));
            }
        }

        return response()->json(['message' => 'تم تحديث الوصفة', 'data' => new PrescriptionResource($prescription->load('items'))]);
    }

    public function markPrinted(Prescription $prescription): JsonResponse
    {
        $prescription->increment('print_count');
        $prescription->update(['is_printed' => true]);
        return response()->json(['message' => 'تم تحديث حالة الطباعة', 'print_count' => $prescription->print_count]);
    }

    public function downloadPdf(Prescription $prescription)
    {
        $prescription->load(['patient', 'doctor', 'visit', 'items']);
        $prescription->increment('print_count');
        $prescription->update(['is_printed' => true]);

        $pdf = Pdf::loadView('pdf.prescription', ['prescription' => $prescription])
            ->setPaper('a4', 'portrait');

        return $pdf->stream("prescription-{$prescription->prescription_number}.pdf");
    }

    public function destroy(Prescription $prescription): JsonResponse
    {
        $prescription->delete();

        return response()->json(['message' => 'تم حذف الوصفة']);
    }
}
