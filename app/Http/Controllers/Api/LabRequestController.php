<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabRequestStore;
use App\Http\Requests\LabRequestUpdateRequest;
use App\Http\Resources\LabRequestResource;
use App\Models\LabRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LabRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = LabRequest::with(['patient', 'doctor', 'visit']);

        if ($request->filled('patient_id')) $query->where('patient_id', $request->patient_id);
        if ($request->filled('visit_id')) $query->where('visit_id', $request->visit_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('urgency')) $query->where('urgency', $request->urgency);
        if ($request->filled('search')) {
            $query->whereHas('patient', fn($q) => $q->where('full_name', 'LIKE', "%{$request->search}%"));
        }

        $requests = $query->orderBy('request_date', 'desc')->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => LabRequestResource::collection($requests),
            'meta' => ['total' => $requests->total(), 'page' => $requests->currentPage(), 'last_page' => $requests->lastPage()],
        ]);
    }

    public function store(LabRequestStore $request): JsonResponse
    {
        $labRequest = LabRequest::create($request->validated());

        if ($labRequest->visit_id) {
            $labRequest->visit()->update(['status' => 'فحوصات مطلوبة']);
        }

        return response()->json([
            'message' => 'تم طلب الفحوصات بنجاح',
            'data' => new LabRequestResource($labRequest->load(['patient', 'doctor', 'visit'])),
        ], 201);
    }

    public function show(LabRequest $labRequest): JsonResponse
    {
        $labRequest->load(['patient', 'doctor', 'visit', 'results']);
        return response()->json(new LabRequestResource($labRequest));
    }

    public function update(LabRequestUpdateRequest $request, LabRequest $labRequest): JsonResponse
    {
        $labRequest->update($request->validated());
        return response()->json(['message' => 'تم تحديث الطلب', 'data' => new LabRequestResource($labRequest->load(['patient', 'doctor', 'visit']))]);
    }

    public function updateStatus(Request $request, LabRequest $labRequest): JsonResponse
    {
        $request->validate(['status' => 'required|in:مطلوب,تم السحب,في المختبر,نتائج جاهزة,تم التسليم,منتهي,ملغى']);
        $labRequest->update(['status' => $request->status]);

        if ($request->status === 'نتائج جاهزة' && $labRequest->visit_id) {
            $labRequest->visit()->update(['status' => 'نتائج جاهزة']);
        }

        return response()->json(['message' => 'تم تحديث حالة الطلب']);
    }

    public function destroy(LabRequest $labRequest): JsonResponse
    {
        $labRequest->delete();

        return response()->json(['message' => 'تم حذف طلب الفحوصات']);
    }

    public function downloadPdf(LabRequest $labRequest)
    {
        $labRequest->load(['patient', 'doctor', 'visit', 'results']);
        $pdf = Pdf::loadView('pdf.lab-request', ['labRequest' => $labRequest])
            ->setPaper('a4', 'portrait');

        return $pdf->stream("lab-request-{$labRequest->request_number}.pdf");
    }
}
