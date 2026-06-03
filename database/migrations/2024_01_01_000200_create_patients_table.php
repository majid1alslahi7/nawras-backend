<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('file_number', 20)->unique()->comment('M-00001');
            $table->string('full_name', 100);
            $table->string('phone', 20)->unique();
            $table->string('phone2', 20)->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['ذكر', 'أنثى'])->nullable();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('national_id', 20)->nullable()->unique();
            $table->string('email', 100)->nullable();
            $table->string('occupation', 100)->nullable();
            $table->enum('marital_status', ['أعزب', 'متزوج', 'مطلق', 'أرمل'])->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->fullText(['full_name', 'phone', 'address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
