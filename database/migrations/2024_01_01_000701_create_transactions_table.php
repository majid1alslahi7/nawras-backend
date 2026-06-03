<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('transaction_categories');
            $table->foreignId('visit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('entered_by')->constrained('users');
            $table->dateTime('transaction_date')->useCurrent();
            $table->enum('type', ['إيراد', 'مصروف']);
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['نقدي', 'بطاقة ائتمان', 'تحويل بنكي', 'شيك', 'محفظة إلكترونية'])->default('نقدي');
            $table->text('description')->nullable();
            $table->string('receipt_number', 50)->nullable()->unique();
            $table->string('receipt_image_path')->nullable();
            $table->boolean('is_reconciled')->default(false);
            $table->dateTime('reconciled_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['transaction_date', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
