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
        Schema::create('hsk_lesson_practice_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_id')->constrained('hsk_lesson_practices')->onDelete('cascade');
            $table->text('question');
            $table->json('options');
            $table->unsignedInteger('correct');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_lesson_practice_questions');
    }
};
