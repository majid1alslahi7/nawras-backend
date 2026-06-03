<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('specialty', 100)->nullable()->comment('التخصص الطبي');
            $table->string('license_number', 50)->nullable()->comment('رقم الترخيص');
            $table->string('qualification')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('clinic_name', 150)->nullable();
            $table->string('signature_image')->nullable();
            $table->json('working_hours_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
