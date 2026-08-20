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
        Schema::create('hsk_mock_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_level_id')->constrained('hsk_levels')->cascadeOnDelete();
            $table->string('title');
            $table->integer('duration')->comment('Duration in minutes');
            $table->integer('total_questions')->default(0);
            $table->integer('total_score')->default(0);
            $table->integer('pass_score')->default(0);
            $table->string('audio_file')->nullable()->comment('Global audio file for the whole exam');
            $table->integer('view_count')->default(0);
            $table->integer('attempt_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_mock_exams');
    }
};
