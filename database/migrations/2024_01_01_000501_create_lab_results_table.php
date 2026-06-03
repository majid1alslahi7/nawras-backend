<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('entered_by')->constrained('users');
            $table->dateTime('result_date')->useCurrent();
            $table->string('lab_name', 150)->nullable();
            $table->string('lab_reference', 50)->nullable();
            $table->json('results_json');
            $table->string('report_image_path')->nullable();
            $table->boolean('is_abnormal')->default(false);
            $table->boolean('doctor_reviewed')->default(false);
            $table->dateTime('doctor_reviewed_at')->nullable();
            $table->foreignId('doctor_reviewed_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
