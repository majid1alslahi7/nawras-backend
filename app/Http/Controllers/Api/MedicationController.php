<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $medications = Medication::query()
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('trade_name', 'LIKE', "%{$search}%")
                        ->orWhere('generic_name', 'LIKE', "%{$search}%")
                        ->orWhere('concentration', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('trade_name')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'data' => $medications->items(),
            'meta' => [
                'total' => $medications->total(),
                'page' => $medications->currentPage(),
                'last_page' => $medications->lastPage(),
            ],
        ]);
    }

    public function dropdown(Request $request): JsonResponse
    {
        $medications = Medication::query()
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('trade_name', 'LIKE', "%{$search}%")
                        ->orWhere('generic_name', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('trade_name')
            ->limit(30)
            ->get();

        return response()->json($medications);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatePayload($request);
        $medication = Medication::create($data + ['is_active' => true]);

        return response()->json(['message' => 'تمت إضافة الدواء', 'data' => $medication], 201);
    }

    public function show(Medication $medication): JsonResponse
    {
        return response()->json($medication);
    }

    public function update(Request $request, Medication $medication): JsonResponse
    {
        $medication->update($this->validatePayload($request));

        return response()->json(['message' => 'تم تحديث الدواء', 'data' => $medication]);
    }

    public function destroy(Medication $medication): JsonResponse
    {
        $medication->update(['is_active' => false]);

        return response()->json(['message' => 'تم تعطيل الدواء']);
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'trade_name' => ['required', 'string', 'max:150'],
            'generic_name' => ['nullable', 'string', 'max:150'],
            'concentration' => ['nullable', 'string', 'max:80'],
            'form' => ['nullable', 'string', 'max:80'],
            'default_dosage' => ['nullable', 'string', 'max:100'],
            'default_frequency' => ['nullable', 'string', 'max:100'],
            'default_duration' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
