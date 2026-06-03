<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained()->cascadeOnDelete();
            $table->integer('blood_pressure_sys')->nullable();
            $table->integer('blood_pressure_dia')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->decimal('temperature', 3, 1)->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->integer('oxygen_saturation')->nullable();
            $table->decimal('blood_sugar', 5, 1)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->decimal('bmi', 4, 1)->nullable();
            $table->integer('pain_level')->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('measured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_vitals');
    }
};
