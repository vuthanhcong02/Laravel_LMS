<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\QuizType;
use App\Enums\QuestionType;

/**
 * Migration to update quiz, questions and answers tables for the new exam module.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update quizzes table
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('type')->default(QuizType::MULTIPLE_CHOICE->value)->after('title');
        });

        // Update questions table
        Schema::table('questions', function (Blueprint $table) {
            $table->string('type')->default(QuestionType::MULTIPLE_CHOICE->value)->after('quiz_id');
            $table->string('image_path')->nullable()->after('question_text');
            $table->string('audio_path')->nullable()->after('image_path');
        });

        // Update quiz_attempt_answers table
        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->text('text_answer')->nullable()->after('option_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['type', 'image_path', 'audio_path']);
        });

        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->dropColumn('text_answer');
        });
    }
};
