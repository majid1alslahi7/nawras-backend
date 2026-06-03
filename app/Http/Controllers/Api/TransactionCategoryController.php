<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionCategory;
use Illuminate\Http\JsonResponse;

class TransactionCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = TransactionCategory::where('is_active', true)
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get()
            ->map(fn (TransactionCategory $category) => [
                'id' => $category->id,
                'name_ar' => $category->name_ar,
                'name' => $category->name_ar,
                'name_en' => $category->name_en,
                'type' => $category->type,
                'icon' => $category->icon,
                'color' => $category->color,
            ]);

        return response()->json($categories);
    }
}
