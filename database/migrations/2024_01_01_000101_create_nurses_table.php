<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nurses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('position', 100)->default('ممرض');
            $table->string('employee_id', 50)->nullable();
            $table->enum('shift', ['صباحي', 'مسائي', 'كامل'])->default('صباحي');
            $table->boolean('can_manage_finances')->default(true);
            $table->boolean('can_manage_appointments')->default(true);
            $table->boolean('can_enter_results')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
