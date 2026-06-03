<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_requests', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->foreignId('visit_id')->nullable()->change();
            $table->foreign('visit_id')->references('id')->on('visits')->nullOnDelete();
        });

        Schema::table('lab_results', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->foreignId('visit_id')->nullable()->change();
            $table->foreign('visit_id')->references('id')->on('visits')->nullOnDelete();
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->foreignId('visit_id')->nullable()->change();
            $table->foreign('visit_id')->references('id')->on('visits')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->foreignId('visit_id')->nullable(false)->change();
            $table->foreign('visit_id')->references('id')->on('visits')->cascadeOnDelete();
        });

        Schema::table('lab_results', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->foreignId('visit_id')->nullable(false)->change();
            $table->foreign('visit_id')->references('id')->on('visits')->cascadeOnDelete();
        });

        Schema::table('lab_requests', function (Blueprint $table) {
            $table->dropForeign(['visit_id']);
            $table->foreignId('visit_id')->nullable(false)->change();
            $table->foreign('visit_id')->references('id')->on('visits')->cascadeOnDelete();
        });
    }
};
