<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            SystemSetting::orderBy('setting_key')->get()
        );
    }

    public function update(Request $request): JsonResponse
    {
        $settings = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.setting_key' => ['required', 'string', 'max:100'],
            'settings.*.setting_value' => ['nullable'],
            'settings.*.setting_type' => ['nullable', 'in:text,number,boolean,json,file'],
            'settings.*.description' => ['nullable', 'string', 'max:255'],
        ])['settings'];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['setting_key' => $setting['setting_key']],
                [
                    'setting_value' => $setting['setting_value'] ?? null,
                    'setting_type' => $setting['setting_type'] ?? 'text',
                    'description' => $setting['description'] ?? null,
                    'updated_by' => auth()->id(),
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json([
            'message' => 'تم تحديث الإعدادات',
            'data' => SystemSetting::orderBy('setting_key')->get(),
        ]);
    }
}
