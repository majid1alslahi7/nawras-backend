<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token', 500)->unique();
            $table->enum('token_type', ['access', 'refresh', 'remember'])->default('access');
            $table->string('device_info')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('expires_at');
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['user_id', 'token_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tokens');
    }
};
