<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Nurse;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // الطبيبة نورس
        $doctor = User::create([
            'full_name' => 'د. نورس محمد اليمني',
            'email' => 'dr.nawras@nawras-clinic.com',
            'phone' => '777123456',
            'password' => Hash::make('password123'),
            'role' => 'doctor',
            'is_active' => true,
        ]);
        $doctor->assignRole('doctor');
        Doctor::create([
            'user_id' => $doctor->id,
            'specialty' => 'طب عام وطب أسرة',
            'license_number' => 'YM-2024-00125',
            'qualification' => 'بكالوريوس طب وجراحة - جامعة صنعاء',
            'experience_years' => 8,
            'clinic_name' => 'عيادة نورس الطبية',
            'working_hours_json' => json_encode([
                'sat' => ['start' => '08:00', 'end' => '14:00'],
                'sun' => ['start' => '08:00', 'end' => '14:00'],
                'mon' => ['start' => '08:00', 'end' => '14:00'],
                'tue' => ['start' => '08:00', 'end' => '14:00'],
                'wed' => ['start' => '08:00', 'end' => '14:00'],
                'thu' => ['start' => '08:00', 'end' => '12:00'],
                'fri' => null,
            ]),
        ]);

        // الممرضة أروى
        $nurse = User::create([
            'full_name' => 'أروى عبدالله السلامي',
            'email' => 'arwa@nawras-clinic.com',
            'phone' => '777987654',
            'password' => Hash::make('password123'),
            'role' => 'nurse',
            'is_active' => true,
        ]);
        $nurse->assignRole('nurse');
        Nurse::create([
            'user_id' => $nurse->id,
            'position' => 'ممرضة مسؤولة',
            'employee_id' => 'NRS-001',
            'shift' => 'صباحي',
            'can_manage_finances' => true,
            'can_manage_appointments' => true,
            'can_enter_results' => true,
        ]);

        // المدير
        $admin = User::create([
            'full_name' => 'م. ماجد السلامي',
            'email' => 'admin@nawras-clinic.com',
            'phone' => '777555555',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $admin->assignRole('admin');
    }
}
