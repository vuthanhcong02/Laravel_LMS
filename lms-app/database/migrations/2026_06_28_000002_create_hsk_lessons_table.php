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
        Schema::create('hsk_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_level_id')->constrained('hsk_levels')->onDelete('cascade');
            $table->unsignedInteger('lesson_number');
            $table->string('title', 150); // 你好
            $table->string('pinyin', 200)->nullable(); // Nǐ hǎo
            $table->string('translation', 200)->nullable(); // Xin chào
            $table->string('code', 50)->nullable(); // H1L01
            $table->timestamps();

            $table->unique(['hsk_level_id', 'lesson_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_lessons');
    }
};
