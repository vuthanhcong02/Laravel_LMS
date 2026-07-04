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
        // Drop old tables if they exist
        Schema::dropIfExists('hsk_lesson_practice_questions');
        Schema::dropIfExists('hsk_lesson_practices');

        Schema::create('hsk_lesson_practices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_lesson_id')->constrained('hsk_lessons')->onDelete('cascade');
            $table->enum('type', ['listening', 'reading']);
            $table->string('audio_path', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('hsk_lesson_practice_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained('hsk_lesson_practices')->onDelete('cascade');
            $table->string('section_han', 255)->nullable();
            $table->text('section_vi')->nullable();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('hsk_lesson_practice_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('hsk_lesson_practice_sections')->onDelete('cascade');
            $table->unsignedInteger('ques_id'); // 1, 2, 3...
            $table->string('ques_type', 50); // true_false, multiple_choices
            $table->text('question')->nullable();
            $table->string('question_pinyin', 255)->nullable();
            $table->json('options')->nullable();
            $table->string('correct_answer', 255)->nullable();
            $table->string('image_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_lesson_practice_questions');
        Schema::dropIfExists('hsk_lesson_practice_sections');
        Schema::dropIfExists('hsk_lesson_practices');

        // Recreate the old schema in down() just in case
        Schema::create('hsk_lesson_practices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_lesson_id')->constrained('hsk_lessons')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('desc')->nullable();
            $table->timestamps();
        });

        Schema::create('hsk_lesson_practice_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained('hsk_lesson_practices')->onDelete('cascade');
            $table->text('question');
            $table->json('options');
            $table->unsignedInteger('correct');
            $table->timestamps();
        });
    }
};
