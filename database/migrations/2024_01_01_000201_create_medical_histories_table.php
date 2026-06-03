<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('chronic_diseases')->nullable();
            $table->text('allergies')->nullable();
            $table->text('previous_surgeries')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('family_history')->nullable();
            $table->enum('smoking_status', ['غير مدخن', 'مدخن', 'مدخن سابق'])->default('غير مدخن');
            $table->enum('alcohol_status', ['لا يشرب', 'يشرب', 'سابق'])->default('لا يشرب');
            $table->boolean('pregnancy_status')->nullable();
            $table->date('last_menstrual_date')->nullable();
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('bmi', 4, 1)->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
