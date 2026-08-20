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
        Schema::create('hsk_mock_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_mock_exam_group_id')->nullable()->constrained('hsk_mock_exam_question_groups')->cascadeOnDelete();
            $table->foreignId('hsk_mock_exam_section_id')->constrained('hsk_mock_exam_sections')->cascadeOnDelete();
            $table->enum('question_type', ['single_choice', 'true_false', 'fill_blank', 'matching', 'essay', 'sentence_reordering']);
            $table->text('title')->nullable();
            $table->string('pinyin')->nullable();
            $table->string('image')->nullable();
            $table->string('audio_file')->nullable();
            $table->integer('points')->default(0);
            $table->text('explanation')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_mock_exam_questions');
    }
};
