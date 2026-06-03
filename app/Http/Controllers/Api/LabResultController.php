<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabResultRequest;
use App\Http\Requests\LabResultUpdateRequest;
use App\Http\Resources\LabResultResource;
use App\Models\LabRequest;
use App\Models\LabResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabResultController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = LabResult::with(['patient', 'labRequest', 'enteredBy']);

        if ($request->filled('patient_id')) $query->where('patient_id', $request->patient_id);
        if ($request->filled('lab_request_id')) $query->where('lab_request_id', $request->lab_request_id);
        if ($request->has('is_abnormal')) $query->where('is_abnormal', $request->boolean('is_abnormal'));
        if ($request->has('doctor_reviewed')) $query->where('doctor_reviewed', $request->boolean('doctor_reviewed'));
        if ($request->filled('search')) {
            $query->whereHas('patient', fn($q) => $q->where('full_name', 'LIKE', "%{$request->search}%"));
        }

        $results = $query->orderBy('result_date', 'desc')->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => LabResultResource::collection($results),
            'meta' => ['total' => $results->total(), 'page' => $results->currentPage(), 'last_page' => $results->lastPage()],
        ]);
    }

    public function unreviewed(): JsonResponse
    {
        $results = LabResult::with(['patient', 'labRequest'])
            ->where('doctor_reviewed', false)
            ->where('is_abnormal', true)
            ->orderBy('result_date', 'asc')
            ->get();

        return response()->json(LabResultResource::collection($results));
    }

    public function store(LabResultRequest $request): JsonResponse
    {
        $data = $request->validated();
        $labRequest = LabRequest::findOrFail($data['lab_request_id']);
        $data['patient_id'] = $data['patient_id'] ?? $labRequest->patient_id;
        $data['visit_id'] = $data['visit_id'] ?? $labRequest->visit_id;

        // رفع صورة التقرير
        if ($request->hasFile('report_image')) {
            $data['report_image_path'] = $request->file('report_image')->store('lab-reports', 'public');
        }

        // تحديد إذا كانت النتائج غير طبيعية
        $data['is_abnormal'] = collect($data['results_json'])->contains('is_abnormal', true);
        $data['entered_by'] = auth()->id();

        $labResult = LabResult::create($data);

        // تحديث حالة طلب الفحوصات
        $labResult->labRequest()->update(['status' => 'نتائج جاهزة']);
        if ($labResult->visit_id) {
            $labResult->visit()->update(['status' => 'نتائج جاهزة']);
        }

        return response()->json([
            'message' => 'تم إدخال النتائج بنجاح',
            'data' => new LabResultResource($labResult->load(['patient', 'labRequest', 'enteredBy'])),
        ], 201);
    }

    public function show(LabResult $labResult): JsonResponse
    {
        $labResult->load(['patient', 'labRequest', 'enteredBy', 'reviewedBy']);
        return response()->json(new LabResultResource($labResult));
    }

    public function update(LabResultUpdateRequest $request, LabResult $labResult): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('report_image')) {
            if ($labResult->report_image_path) {
                Storage::disk('public')->delete($labResult->report_image_path);
            }
            $data['report_image_path'] = $request->file('report_image')->store('lab-reports', 'public');
        }

        if (isset($data['results_json'])) {
            $data['is_abnormal'] = collect($data['results_json'])->contains('is_abnormal', true);
        }

        if (($data['doctor_reviewed'] ?? false) && !$labResult->doctor_reviewed_at) {
            $data['doctor_reviewed_at'] = now();
            $data['doctor_reviewed_by'] = auth()->id();
        }

        $labResult->update($data);

        return response()->json([
            'message' => 'تم تحديث نتيجة الفحوصات',
            'data' => new LabResultResource($labResult->load(['patient', 'labRequest', 'enteredBy', 'reviewedBy'])),
        ]);
    }

    public function destroy(LabResult $labResult): JsonResponse
    {
        if ($labResult->report_image_path) {
            Storage::disk('public')->delete($labResult->report_image_path);
        }

        $labResult->delete();

        return response()->json(['message' => 'تم حذف نتيجة الفحوصات']);
    }

    public function review(Request $request, LabResult $labResult): JsonResponse
    {
        $labResult->update([
            'doctor_reviewed' => true,
            'doctor_reviewed_at' => now(),
            'doctor_reviewed_by' => auth()->id(),
            'notes' => $request->notes ?? $labResult->notes,
        ]);

        return response()->json(['message' => 'تمت مراجعة النتائج من قبل الطبيب']);
    }
}
