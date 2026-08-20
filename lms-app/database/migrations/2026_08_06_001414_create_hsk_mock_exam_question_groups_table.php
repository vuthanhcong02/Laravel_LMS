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
        Schema::create('hsk_mock_exam_question_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_mock_exam_section_id')->constrained('hsk_mock_exam_sections')->cascadeOnDelete();
            $table->text('passage_text')->nullable();
            $table->string('passage_audio')->nullable();
            $table->string('passage_image')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_mock_exam_question_groups');
    }
};
