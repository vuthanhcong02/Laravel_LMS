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
        Schema::create('hsk_mock_exam_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_mock_exam_id')->constrained('hsk_mock_exams')->cascadeOnDelete();
            $table->string('name');
            $table->enum('skill_type', ['listening', 'reading', 'writing']);
            $table->text('description')->nullable();
            $table->string('audio_file')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_mock_exam_sections');
    }
};
