<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (! Schema::hasColumn('appointments', 'paid_transaction_id')) {
                $table->foreignId('paid_transaction_id')->nullable()->after('cancelled_by')->constrained('transactions')->nullOnDelete();
            }
            if (! Schema::hasColumn('appointments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('paid_transaction_id');
            }
            if (! Schema::hasColumn('appointments', 'is_free')) {
                $table->boolean('is_free')->default(false)->after('paid_at');
            }
            if (! Schema::hasColumn('appointments', 'free_until')) {
                $table->date('free_until')->nullable()->after('is_free');
            }
            if (! Schema::hasColumn('appointments', 'payment_status')) {
                $table->string('payment_status', 30)->default('unpaid')->after('free_until');
            }
            if (! Schema::hasColumn('appointments', 'payment_notes')) {
                $table->text('payment_notes')->nullable()->after('payment_status');
            }
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'appointment_id')) {
                $table->foreignId('appointment_id')->nullable()->after('patient_id')->constrained('appointments')->nullOnDelete();
            }
            if (! Schema::hasColumn('transactions', 'receipt_type')) {
                $table->string('receipt_type', 50)->default('general')->after('receipt_number');
            }
        });

        try {
            Schema::table('appointments', function (Blueprint $table) {
                $table->index(['payment_status', 'appointment_date']);
            });
        } catch (\Throwable) {
            //
        }

        try {
            Schema::table('transactions', function (Blueprint $table) {
                $table->index(['receipt_type', 'type']);
            });
        } catch (\Throwable) {
            //
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('appointment_id');
            $table->dropIndex(['receipt_type', 'type']);
            $table->dropColumn('receipt_type');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['payment_status', 'appointment_date']);
            $table->dropConstrainedForeignId('paid_transaction_id');
            $table->dropColumn(['paid_at', 'is_free', 'free_until', 'payment_status', 'payment_notes']);
        });
    }
};
