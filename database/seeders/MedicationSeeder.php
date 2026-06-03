<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $medications = [
            ['trade_name' => 'Paracetamol', 'generic_name' => 'Acetaminophen', 'concentration' => '500mg', 'form' => 'أقراص', 'default_dosage' => 'قرص واحد', 'default_frequency' => 'كل 8 ساعات', 'default_duration' => '3 أيام'],
            ['trade_name' => 'Ibuprofen', 'generic_name' => 'Ibuprofen', 'concentration' => '400mg', 'form' => 'أقراص', 'default_dosage' => 'قرص واحد', 'default_frequency' => 'كل 12 ساعة', 'default_duration' => '3 أيام'],
            ['trade_name' => 'Amoxicillin', 'generic_name' => 'Amoxicillin', 'concentration' => '500mg', 'form' => 'كبسولات', 'default_dosage' => 'كبسولة واحدة', 'default_frequency' => 'كل 8 ساعات', 'default_duration' => '5 أيام'],
            ['trade_name' => 'Cetirizine', 'generic_name' => 'Cetirizine', 'concentration' => '10mg', 'form' => 'أقراص', 'default_dosage' => 'قرص واحد', 'default_frequency' => 'مرة يوميا', 'default_duration' => '5 أيام'],
            ['trade_name' => 'Omeprazole', 'generic_name' => 'Omeprazole', 'concentration' => '20mg', 'form' => 'كبسولات', 'default_dosage' => 'كبسولة واحدة', 'default_frequency' => 'قبل الإفطار', 'default_duration' => '14 يوما'],
            ['trade_name' => 'Metformin', 'generic_name' => 'Metformin', 'concentration' => '500mg', 'form' => 'أقراص', 'default_dosage' => 'قرص واحد', 'default_frequency' => 'بعد الأكل', 'default_duration' => 'حسب الخطة'],
        ];

        foreach ($medications as $medication) {
            Medication::updateOrCreate(
                ['trade_name' => $medication['trade_name'], 'concentration' => $medication['concentration']],
                $medication + ['is_active' => true]
            );
        }
    }
}
