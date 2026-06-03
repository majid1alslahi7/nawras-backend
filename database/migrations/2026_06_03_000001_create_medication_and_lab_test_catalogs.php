<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('medications')) {
            Schema::create('medications', function (Blueprint $table) {
                $table->id();
                $table->string('trade_name', 150);
                $table->string('generic_name', 150)->nullable();
                $table->string('concentration', 80)->nullable();
                $table->string('form', 80)->nullable();
                $table->string('default_dosage', 100)->nullable();
                $table->string('default_frequency', 100)->nullable();
                $table->string('default_duration', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['trade_name', 'is_active']);
            });
        } else {
            Schema::table('medications', function (Blueprint $table) {
                if (! Schema::hasColumn('medications', 'default_dosage')) {
                    $table->string('default_dosage', 100)->nullable()->after('form');
                }
                if (! Schema::hasColumn('medications', 'default_frequency')) {
                    $table->string('default_frequency', 100)->nullable()->after('default_dosage');
                }
                if (! Schema::hasColumn('medications', 'default_duration')) {
                    $table->string('default_duration', 100)->nullable()->after('default_frequency');
                }
            });
        }

        if (! Schema::hasTable('lab_tests')) {
            Schema::create('lab_tests', function (Blueprint $table) {
                $table->id();
                $table->string('test_name', 150);
                $table->string('test_code', 50)->nullable();
                $table->string('category', 80)->nullable();
                $table->string('normal_range', 150)->nullable();
                $table->string('unit', 50)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['test_name', 'category', 'is_active']);
            });
        } else {
            Schema::table('lab_tests', function (Blueprint $table) {
                if (! Schema::hasColumn('lab_tests', 'normal_range')) {
                    $table->string('normal_range', 150)->nullable()->after('category');
                }
                if (! Schema::hasColumn('lab_tests', 'unit')) {
                    $table->string('unit', 50)->nullable()->after('normal_range');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('medications');
    }
};
