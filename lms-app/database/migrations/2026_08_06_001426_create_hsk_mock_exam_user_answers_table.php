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
        Schema::create('hsk_mock_exam_user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_mock_exam_result_id')->constrained('hsk_mock_exam_results')->cascadeOnDelete();
            $table->foreignId('hsk_mock_exam_question_id')->constrained('hsk_mock_exam_questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->nullable()->constrained('hsk_mock_exam_options')->nullOnDelete();
            $table->text('text_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_mock_exam_user_answers');
    }
};
