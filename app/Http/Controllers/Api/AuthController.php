<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'رقم الهاتف أو كلمة المرور غير صحيحة'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'الحساب غير نشط، راجع المدير'], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'role' => $user->role,
                'phone' => $user->phone,
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
            'token' => $token,
        ]);
    }

    public function me(): JsonResponse
    {
        $user = auth()->user()->load('doctor', 'nurse');
        return response()->json(['user' => $user]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج']);
    }
}
