<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->time('end_time_expected')->nullable();
            $table->string('visit_reason', 255);
            $table->enum('visit_type', ['كشف جديد', 'متابعة', 'عرض نتائج', 'استشارة', 'طارئ', 'إجراء'])->default('كشف جديد');
            $table->enum('status', ['مؤكد', 'قيد الانتظار', 'حضر', 'جاري الكشف', 'مكتمل', 'ملغى', 'لم يحضر'])->default('مؤكد');
            $table->enum('priority', ['عادي', 'عاجل', 'طارئ'])->default('عادي');
            $table->text('notes')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->boolean('is_reminder_sent')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('cancelled_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->index(['appointment_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
