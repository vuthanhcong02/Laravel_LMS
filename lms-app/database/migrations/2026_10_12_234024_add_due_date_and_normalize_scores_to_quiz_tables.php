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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dateTime('due_date')->nullable()->after('time_limit');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->decimal('raw_score', 5, 2)->nullable()->after('score');
            $table->decimal('total_marks', 5, 2)->nullable()->after('raw_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn(['raw_score', 'total_marks']);
        });

        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('due_date');
        });
    }
};
