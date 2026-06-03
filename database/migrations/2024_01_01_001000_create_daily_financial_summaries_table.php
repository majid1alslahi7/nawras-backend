<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_financial_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('summary_date')->unique();
            $table->decimal('total_income', 12, 2)->default(0.00);
            $table->decimal('total_expense', 12, 2)->default(0.00);
            $table->decimal('net_profit', 12, 2)->default(0.00);
            $table->decimal('cash_income', 12, 2)->default(0.00);
            $table->decimal('card_income', 12, 2)->default(0.00);
            $table->decimal('transfer_income', 12, 2)->default(0.00);
            $table->integer('patient_count')->default(0);
            $table->integer('visit_count')->default(0);
            $table->integer('prescription_count')->default(0);
            $table->integer('lab_request_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_financial_summaries');
    }
};
