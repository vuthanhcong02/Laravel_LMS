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
        Schema::create('user_exp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action_type', 50);
            $table->unsignedInteger('exp_gained');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->index(['user_id', 'action_type', 'created_at']);
            $table->index(['user_id', 'action_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_exp_transactions');
    }
};
