<?php

namespace Database\Seeders;

use App\Models\LabTest;
use Illuminate\Database\Seeder;

class LabTestSeeder extends Seeder
{
    public function run(): void
    {
        $tests = [
            ['test_name' => 'صورة دم كاملة', 'test_code' => 'CBC', 'category' => 'دم', 'normal_range' => 'حسب العمر والجنس', 'unit' => ''],
            ['test_name' => 'سكر صائم', 'test_code' => 'FBS', 'category' => 'كيمياء', 'normal_range' => '70-100', 'unit' => 'mg/dL'],
            ['test_name' => 'سكر تراكمي', 'test_code' => 'HbA1c', 'category' => 'كيمياء', 'normal_range' => '< 5.7', 'unit' => '%'],
            ['test_name' => 'وظائف كبد', 'test_code' => 'LFT', 'category' => 'كيمياء', 'normal_range' => 'حسب المؤشر', 'unit' => 'U/L'],
            ['test_name' => 'وظائف كلى', 'test_code' => 'RFT', 'category' => 'كيمياء', 'normal_range' => 'حسب المؤشر', 'unit' => 'mg/dL'],
            ['test_name' => 'فيتامين د', 'test_code' => 'Vit-D', 'category' => 'فيتامينات', 'normal_range' => '30-100', 'unit' => 'ng/mL'],
            ['test_name' => 'تحليل بول', 'test_code' => 'UA', 'category' => 'بول', 'normal_range' => 'طبيعي', 'unit' => ''],
            ['test_name' => 'أشعة صدر', 'test_code' => 'CXR', 'category' => 'أشعة', 'normal_range' => 'حسب التقرير', 'unit' => ''],
        ];

        foreach ($tests as $test) {
            LabTest::updateOrCreate(
                ['test_code' => $test['test_code']],
                $test + ['is_active' => true]
            );
        }
    }
}
