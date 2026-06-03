<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionCategory;

class TransactionCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // إيرادات
            ['name_ar' => 'كشف طبي', 'name_en' => 'Medical Consultation', 'type' => 'إيراد', 'icon' => '🩺', 'color' => '#4CAF50', 'sort_order' => 1],
            ['name_ar' => 'كشف متابعة', 'name_en' => 'Follow-up Visit', 'type' => 'إيراد', 'icon' => '🔄', 'color' => '#66BB6A', 'sort_order' => 2],
            ['name_ar' => 'كشف طارئ', 'name_en' => 'Emergency Visit', 'type' => 'إيراد', 'icon' => '🚨', 'color' => '#EF5350', 'sort_order' => 3],
            ['name_ar' => 'خدمة تمريض', 'name_en' => 'Nursing Service', 'type' => 'إيراد', 'icon' => '💉', 'color' => '#42A5F5', 'sort_order' => 4],
            ['name_ar' => 'إجراء طبي', 'name_en' => 'Medical Procedure', 'type' => 'إيراد', 'icon' => '🏥', 'color' => '#AB47BC', 'sort_order' => 5],
            ['name_ar' => 'تقرير طبي', 'name_en' => 'Medical Report', 'type' => 'إيراد', 'icon' => '📄', 'color' => '#78909C', 'sort_order' => 6],
            ['name_ar' => 'خدمة طوارئ', 'name_en' => 'Emergency Service', 'type' => 'إيراد', 'icon' => '🆘', 'color' => '#F44336', 'sort_order' => 7],
            ['name_ar' => 'إيراد آخر', 'name_en' => 'Other Income', 'type' => 'إيراد', 'icon' => '💵', 'color' => '#26A69A', 'sort_order' => 99],

            // مصروفات
            ['name_ar' => 'إيجار العيادة', 'name_en' => 'Clinic Rent', 'type' => 'مصروف', 'icon' => '🏠', 'color' => '#FF7043', 'sort_order' => 1],
            ['name_ar' => 'رواتب الموظفين', 'name_en' => 'Staff Salaries', 'type' => 'مصروف', 'icon' => '👥', 'color' => '#FFA726', 'sort_order' => 2],
            ['name_ar' => 'مستلزمات طبية', 'name_en' => 'Medical Supplies', 'type' => 'مصروف', 'icon' => '💊', 'color' => '#EC407A', 'sort_order' => 3],
            ['name_ar' => 'أدوية ومستلزمات', 'name_en' => 'Medicines & Supplies', 'type' => 'مصروف', 'icon' => '🏥', 'color' => '#E91E63', 'sort_order' => 4],
            ['name_ar' => 'كهرباء وماء', 'name_en' => 'Utilities', 'type' => 'مصروف', 'icon' => '💡', 'color' => '#FFCA28', 'sort_order' => 5],
            ['name_ar' => 'اتصالات وإنترنت', 'name_en' => 'Telecom & Internet', 'type' => 'مصروف', 'icon' => '📱', 'color' => '#29B6F6', 'sort_order' => 6],
            ['name_ar' => 'وقود ومواصلات', 'name_en' => 'Fuel & Transport', 'type' => 'مصروف', 'icon' => '⛽', 'color' => '#FF5722', 'sort_order' => 7],
            ['name_ar' => 'صيانة ونظافة', 'name_en' => 'Maintenance & Cleaning', 'type' => 'مصروف', 'icon' => '🔧', 'color' => '#8D6E63', 'sort_order' => 8],
            ['name_ar' => 'دعاية وإعلان', 'name_en' => 'Marketing', 'type' => 'مصروف', 'icon' => '📢', 'color' => '#7E57C2', 'sort_order' => 9],
            ['name_ar' => 'رسوم وتراخيص', 'name_en' => 'Licenses & Fees', 'type' => 'مصروف', 'icon' => '📋', 'color' => '#FFA000', 'sort_order' => 10],
            ['name_ar' => 'مصروف آخر', 'name_en' => 'Other Expense', 'type' => 'مصروف', 'icon' => '💸', 'color' => '#78909C', 'sort_order' => 99],
        ];

        foreach ($categories as $category) {
            TransactionCategory::create($category);
        }
    }
}
