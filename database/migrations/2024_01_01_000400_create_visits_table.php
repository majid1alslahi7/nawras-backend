<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('doctor_id')->constrained('users');
            $table->dateTime('visit_date')->useCurrent();
            $table->text('chief_complaint');
            $table->text('present_illness')->nullable();
            $table->text('diagnosis_initial')->nullable();
            $table->text('diagnosis_final')->nullable();
            $table->string('icd10_code', 20)->nullable();
            $table->text('doctor_notes')->nullable();
            $table->text('plan')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->enum('status', ['قيد الكشف', 'فحوصات مطلوبة', 'في انتظار النتائج', 'نتائج جاهزة', 'مكتمل'])->default('قيد الكشف');
            $table->boolean('is_free')->default(false);
            $table->timestamps();
            $table->index(['patient_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
