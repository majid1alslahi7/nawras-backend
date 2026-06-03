<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['setting_key' => 'clinic_name', 'setting_value' => 'عيادة نورس الطبية', 'setting_type' => 'text', 'description' => 'اسم العيادة'],
            ['setting_key' => 'clinic_name_en', 'setting_value' => 'Nawras Medical Clinic', 'setting_type' => 'text', 'description' => 'اسم العيادة بالإنجليزية'],
            ['setting_key' => 'clinic_phone', 'setting_value' => '014567890', 'setting_type' => 'text', 'description' => 'رقم هاتف العيادة'],
            ['setting_key' => 'clinic_phone2', 'setting_value' => '777123456', 'setting_type' => 'text', 'description' => 'رقم جوال العيادة'],
            ['setting_key' => 'clinic_address', 'setting_value' => 'صنعاء - شارع الزبيري - مقابل البنك اليمني', 'setting_type' => 'text', 'description' => 'عنوان العيادة'],
            ['setting_key' => 'clinic_city', 'setting_value' => 'صنعاء', 'setting_type' => 'text', 'description' => 'المدينة'],
            ['setting_key' => 'clinic_country', 'setting_value' => 'اليمن', 'setting_type' => 'text', 'description' => 'البلد'],
            ['setting_key' => 'clinic_email', 'setting_value' => 'info@nawras-clinic.com', 'setting_type' => 'text', 'description' => 'البريد الإلكتروني'],
            ['setting_key' => 'consultation_fee', 'setting_value' => '5000', 'setting_type' => 'number', 'description' => 'رسوم الكشف الافتراضية (ريال يمني)'],
            ['setting_key' => 'followup_fee', 'setting_value' => '3000', 'setting_type' => 'number', 'description' => 'رسوم المتابعة'],
            ['setting_key' => 'emergency_fee', 'setting_value' => '7000', 'setting_type' => 'number', 'description' => 'رسوم الكشف الطارئ'],
            ['setting_key' => 'currency', 'setting_value' => 'ريال يمني', 'setting_type' => 'text', 'description' => 'العملة'],
            ['setting_key' => 'currency_code', 'setting_value' => 'YER', 'setting_type' => 'text', 'description' => 'رمز العملة الدولي'],
            ['setting_key' => 'currency_symbol', 'setting_value' => '﷼', 'setting_type' => 'text', 'description' => 'رمز العملة'],
            ['setting_key' => 'appointment_interval', 'setting_value' => '20', 'setting_type' => 'number', 'description' => 'مدة الموعد بالدقائق'],
            ['setting_key' => 'working_hours_start', 'setting_value' => '08:00', 'setting_type' => 'text', 'description' => 'بداية الدوام'],
            ['setting_key' => 'working_hours_end', 'setting_value' => '14:00', 'setting_type' => 'text', 'description' => 'نهاية الدوام'],
            ['setting_key' => 'weekend_days', 'setting_value' => '["fri"]', 'setting_type' => 'json', 'description' => 'أيام العطلة'],
            ['setting_key' => 'clinic_logo', 'setting_value' => null, 'setting_type' => 'file', 'description' => 'شعار العيادة'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
