<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users');
            $table->dateTime('request_date')->useCurrent();
            $table->string('request_number', 30)->nullable()->unique();
            $table->json('tests_list_json');
            $table->text('clinical_diagnosis')->nullable();
            $table->enum('urgency', ['عادي', 'عاجل', 'طارئ'])->default('عادي');
            $table->text('notes')->nullable();
            $table->enum('status', ['مطلوب', 'تم السحب', 'في المختبر', 'نتائج جاهزة', 'تم التسليم', 'منتهي', 'ملغى'])->default('مطلوب');
            $table->date('expected_result_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_requests');
    }
};
