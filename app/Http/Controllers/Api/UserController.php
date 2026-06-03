<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function doctorsDropdown(Request $request): JsonResponse
    {
        $doctors = User::query()
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('full_name')
            ->limit($request->get('per_page', 30))
            ->get(['id', 'full_name', 'phone', 'email', 'role']);

        return response()->json($doctors);
    }

    public function doctorOption(User $doctor): JsonResponse
    {
        abort_unless($doctor->role === 'doctor' && $doctor->is_active, 404);

        return response()->json([
            'id' => $doctor->id,
            'full_name' => $doctor->full_name,
            'phone' => $doctor->phone,
            'email' => $doctor->email,
            'role' => $doctor->role,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->orderBy('role')
            ->orderBy('full_name')
            ->paginate($request->get('per_page', 50));

        return response()->json([
            'data' => UserResource::collection($users),
            'meta' => [
                'total' => $users->total(),
                'page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['doctor', 'nurse', 'admin'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create($data + ['is_active' => $data['is_active'] ?? true]);
        $user->syncRoles([$user->role]);

        return response()->json([
            'message' => 'تم إضافة المستخدم بنجاح',
            'data' => new UserResource($user),
        ], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', Rule::in(['doctor', 'nurse', 'admin'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);
        $user->syncRoles([$user->role]);

        return response()->json([
            'message' => 'تم تحديث المستخدم بنجاح',
            'data' => new UserResource($user),
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'لا يمكن حذف المستخدم الحالي'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'تم حذف المستخدم']);
    }
}
