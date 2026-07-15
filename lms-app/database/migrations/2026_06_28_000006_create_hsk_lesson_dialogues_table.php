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
        Schema::create('hsk_lesson_dialogues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dialogue_section_id')->constrained('hsk_lesson_dialogue_sections')->onDelete('cascade');
            $table->string('role', 10);
            $table->text('character');
            $table->text('pinyin')->nullable();
            $table->text('translation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_lesson_dialogues');
    }
};
