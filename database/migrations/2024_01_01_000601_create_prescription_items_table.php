<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->integer('order_number')->default(1);
            $table->string('medication_name', 200);
            $table->string('concentration', 100)->nullable();
            $table->string('dosage', 100);
            $table->string('frequency', 100);
            $table->string('duration', 100)->nullable();
            $table->string('quantity', 50)->nullable();
            $table->enum('route', ['فموي', 'موضعي', 'حقن', 'وريدي', 'عضلي', 'تحت الجلد', 'شرجي', 'استنشاق', 'عين', 'أذن'])->default('فموي');
            $table->enum('timing', ['قبل الأكل', 'بعد الأكل', 'مع الأكل', 'عند النوم', 'عند اللزوم', 'غير محدد'])->default('بعد الأكل');
            $table->string('instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
