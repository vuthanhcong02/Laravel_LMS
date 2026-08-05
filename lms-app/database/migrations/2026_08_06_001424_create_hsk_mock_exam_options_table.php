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
        Schema::create('hsk_mock_exam_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_mock_exam_question_id')->constrained('hsk_mock_exam_questions')->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->string('pinyin')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_mock_exam_options');
    }
};
