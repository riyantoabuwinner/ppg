<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('help_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_code', 32)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['calling', 'active', 'resolved', 'closed'])->default('calling')->index();
            $table->string('topic');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            // Indexes for quick lookups and real-time polling
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index(['admin_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_sessions');
    }
};
