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
        Schema::create('quizz_user_answer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('quizz_id')->constrained('quizzes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('quizz_questions')->onUpdate('cascade')->onDelete('cascade');
            $table->string('user_answer');
            $table->float('score', 8, 2); // 8 là tổng số chữ số, 2 là số chữ số sau dấu thập phân
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizz_user_answer');
    }
};
