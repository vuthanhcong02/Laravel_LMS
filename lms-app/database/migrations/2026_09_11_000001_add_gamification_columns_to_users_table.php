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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('current_streak')->default(0)->after('provider_id');
            $table->unsignedInteger('longest_streak')->default(0)->after('current_streak');
            $table->date('last_learning_date')->nullable()->after('longest_streak');
            $table->unsignedBigInteger('exp_total')->default(0)->after('last_learning_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_streak', 'longest_streak', 'last_learning_date', 'exp_total']);
        });
    }
};
