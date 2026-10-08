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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('essay_grading_type')->default('manual')->after('marks'); // 'auto' | 'manual'
            $table->text('correct_answer_text')->nullable()->after('essay_grading_type');
            $table->boolean('case_sensitive')->default(false)->after('correct_answer_text');
        });

        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->decimal('marks_obtained', 5, 2)->nullable()->after('text_answer');
            $table->boolean('is_correct')->nullable()->after('marks_obtained');
            $table->text('teacher_feedback')->nullable()->after('is_correct');
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->string('grading_status')->default('graded')->after('score'); // 'graded' | 'needs_grading'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['essay_grading_type', 'correct_answer_text', 'case_sensitive']);
        });

        Schema::table('quiz_attempt_answers', function (Blueprint $table) {
            $table->dropColumn(['marks_obtained', 'is_correct', 'teacher_feedback']);
        });

        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('grading_status');
        });
    }
};
