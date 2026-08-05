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
        Schema::create('hsk_mock_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('hsk_mock_exam_id')->constrained('hsk_mock_exams')->cascadeOnDelete();
            $table->integer('listening_score')->default(0);
            $table->integer('reading_score')->default(0);
            $table->integer('writing_score')->default(0);
            $table->integer('total_score')->default(0);
            $table->enum('status', ['in_progress', 'grading', 'completed'])->default('in_progress');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_mock_exam_results');
    }
};
