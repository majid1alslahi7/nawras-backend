<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\PatientUpdateRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Patient::query()->with('medicalHistory');

        // فلترة ذكية
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('gender')) {
            $query->byGender($request->gender);
        }
        if ($request->filled('blood_type')) {
            $query->byBloodType($request->blood_type);
        }
        if ($request->filled('age_from') && $request->filled('age_to')) {
            $query->byAgeRange($request->age_from, $request->age_to);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        // ترتيب ذكي
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['created_at', 'full_name', 'birth_date', 'file_number'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $patients = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => PatientResource::collection($patients),
            'meta' => [
                'total' => $patients->total(),
                'page' => $patients->currentPage(),
                'last_page' => $patients->lastPage(),
            ],
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $patients = Patient::search($request->get('q', ''))->take(15)->get();
        return response()->json(PatientResource::collection($patients));
    }

    public function store(PatientRequest $request): JsonResponse
    {
        $patient = Patient::create($request->validated());

        if ($request->filled('medical_history')) {
            $patient->medicalHistory()->create($request->medical_history);
        }

        return response()->json([
            'message' => 'تم إضافة المريض بنجاح',
            'data' => new PatientResource($patient->load('medicalHistory')),
        ], 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        $patient->load(['medicalHistory', 'visits' => fn($q) => $q->recent()->take(10), 'appointments' => fn($q) => $q->upcoming()->take(5)]);
        return response()->json(new PatientResource($patient));
    }

    public function update(PatientUpdateRequest $request, Patient $patient): JsonResponse
    {
        $patient->update($request->validated());

        if ($request->filled('medical_history')) {
            $patient->medicalHistory()->updateOrCreate(
                ['patient_id' => $patient->id],
                $request->medical_history
            );
        }

        return response()->json(['message' => 'تم تحديث بيانات المريض', 'data' => new PatientResource($patient->load('medicalHistory'))]);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();
        return response()->json(['message' => 'تم حذف المريض']);
    }

    public function getDropdown(Request $request): JsonResponse
    {
        $patients = Patient::query()
            ->select('id', 'full_name', 'phone', 'file_number')
            ->when($request->filled('search'), fn ($query) => $query->search($request->search))
            ->orderBy('full_name')
            ->limit($request->get('per_page', 30))
            ->get();

        return response()->json($patients);
    }

    public function dropdownOption(Patient $patient): JsonResponse
    {
        return response()->json([
            'id' => $patient->id,
            'full_name' => $patient->full_name,
            'phone' => $patient->phone,
            'file_number' => $patient->file_number,
        ]);
    }
}
