<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\MedicalHistory;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'full_name' => 'أحمد محمد الصبري',
                'phone' => '777111222',
                'phone2' => null,
                'address' => 'صنعاء - شارع القاهرة - جوار مسجد الصالح',
                'birth_date' => '1990-05-15',
                'gender' => 'ذكر',
                'blood_type' => 'O+',
                'national_id' => '1001001001',
                'occupation' => 'معلم',
                'marital_status' => 'متزوج',
                'emergency_contact_name' => 'فاطمة الصبري',
                'emergency_contact_phone' => '777333444',
                'notes' => 'مريض منتظم - يعاني من حساسية موسمية',
                'medical' => [
                    'chronic_diseases' => 'لا يوجد',
                    'allergies' => 'حساسية بنسلين - حساسية ربيعية',
                    'previous_surgeries' => 'استئصال زائدة دودية 2018',
                    'current_medications' => 'سيتريزين 10 عند اللزوم',
                    'smoking_status' => 'غير مدخن',
                ],
            ],
            [
                'full_name' => 'نورة علي الحميري',
                'phone' => '777222333',
                'phone2' => '777444555',
                'address' => 'صنعاء - حي الحصبة - شارع الأربعين',
                'birth_date' => '1985-12-20',
                'gender' => 'أنثى',
                'blood_type' => 'A+',
                'national_id' => '2002002002',
                'occupation' => 'ربة منزل',
                'marital_status' => 'متزوج',
                'emergency_contact_name' => 'علي الحميري',
                'emergency_contact_phone' => '777555666',
                'notes' => 'متابعة حمل سابقة',
                'medical' => [
                    'chronic_diseases' => 'سكري حمل سابق',
                    'allergies' => 'لا يوجد',
                    'previous_surgeries' => 'ولادة قيصرية 2020',
                    'current_medications' => 'فيتامينات',
                    'smoking_status' => 'غير مدخن',
                ],
            ],
            [
                'full_name' => 'خالد سعيد الأكوع',
                'phone' => '777333444',
                'address' => 'صنعاء - شارع تعز - خلف المستشفى الجمهوري',
                'birth_date' => '1978-03-10',
                'gender' => 'ذكر',
                'blood_type' => 'B+',
                'national_id' => '3003003003',
                'occupation' => 'تاجر',
                'marital_status' => 'متزوج',
                'emergency_contact_name' => 'سامية الأكوع',
                'emergency_contact_phone' => '777666777',
                'notes' => 'يعاني من ضغط الدم',
                'medical' => [
                    'chronic_diseases' => 'ارتفاع ضغط الدم - كولسترول مرتفع',
                    'allergies' => 'لا يوجد',
                    'previous_surgeries' => 'لا يوجد',
                    'current_medications' => 'أملوديبين 5 ملغ يومياً - أتورفاستاتين 20 ملغ',
                    'smoking_status' => 'مدخن',
                ],
            ],
            [
                'full_name' => 'مريم يحيى المتوكل',
                'phone' => '777444555',
                'address' => 'صنعاء - شارع هائل - أمام جامعة صنعاء',
                'birth_date' => '2000-07-25',
                'gender' => 'أنثى',
                'blood_type' => 'AB-',
                'national_id' => '4004004004',
                'occupation' => 'طالبة جامعية',
                'marital_status' => 'أعزب',
                'emergency_contact_name' => 'يحيى المتوكل',
                'emergency_contact_phone' => '777777888',
                'notes' => 'تعاني من فقر دم',
                'medical' => [
                    'chronic_diseases' => 'أنيميا نقص حديد',
                    'allergies' => 'حساسية أتربة وغبار',
                    'previous_surgeries' => 'لا يوجد',
                    'current_medications' => 'حديد + فيتامين سي',
                    'smoking_status' => 'غير مدخن',
                ],
            ],
            [
                'full_name' => 'عبدالملك صالح العنسي',
                'phone' => '777555666',
                'address' => 'صنعاء - منطقة الجراف - شارع المطار',
                'birth_date' => '1965-09-01',
                'gender' => 'ذكر',
                'blood_type' => 'O-',
                'national_id' => '5005005005',
                'occupation' => 'متقاعد',
                'marital_status' => 'متزوج',
                'emergency_contact_name' => 'أمل العنسي',
                'emergency_contact_phone' => '777888999',
                'notes' => 'مريض سكري - يحتاج متابعة دورية',
                'medical' => [
                    'chronic_diseases' => 'سكري نوع 2 - ضغط - روماتيزم مفاصل',
                    'allergies' => 'لا يوجد',
                    'previous_surgeries' => 'قسطرة قلبية 2022',
                    'current_medications' => 'ميتفورمين 500 - إنسولين - ليسينوبريل',
                    'smoking_status' => 'مدخن سابق',
                ],
            ],
        ];

        foreach ($patients as $data) {
            $medical = $data['medical'];
            unset($data['medical']);

            $patient = Patient::create($data);

            MedicalHistory::create(array_merge([
                'patient_id' => $patient->id,
                'alcohol_status' => 'لا يشرب',
            ], $medical));
        }
    }
}
