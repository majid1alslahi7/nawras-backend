<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tests = LabTest::query()
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('test_name', 'LIKE', "%{$search}%")
                        ->orWhere('test_code', 'LIKE', "%{$search}%")
                        ->orWhere('category', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('category')
            ->orderBy('test_name')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'data' => $tests->items(),
            'meta' => [
                'total' => $tests->total(),
                'page' => $tests->currentPage(),
                'last_page' => $tests->lastPage(),
            ],
        ]);
    }

    public function dropdown(Request $request): JsonResponse
    {
        $tests = LabTest::query()
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('test_name', 'LIKE', "%{$search}%")
                        ->orWhere('category', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('category')
            ->orderBy('test_name')
            ->limit(30)
            ->get();

        return response()->json($tests);
    }

    public function store(Request $request): JsonResponse
    {
        $test = LabTest::create($this->validatePayload($request) + ['is_active' => true]);

        return response()->json(['message' => 'تمت إضافة الفحص', 'data' => $test], 201);
    }

    public function show(LabTest $labTest): JsonResponse
    {
        return response()->json($labTest);
    }

    public function update(Request $request, LabTest $labTest): JsonResponse
    {
        $labTest->update($this->validatePayload($request));

        return response()->json(['message' => 'تم تحديث الفحص', 'data' => $labTest]);
    }

    public function destroy(LabTest $labTest): JsonResponse
    {
        $labTest->update(['is_active' => false]);

        return response()->json(['message' => 'تم تعطيل الفحص']);
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'test_name' => ['required', 'string', 'max:150'],
            'test_code' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:80'],
            'normal_range' => ['nullable', 'string', 'max:150'],
            'unit' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
