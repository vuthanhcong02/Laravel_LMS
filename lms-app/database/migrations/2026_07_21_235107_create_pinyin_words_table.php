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
        Schema::create('pinyin_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinyin_tone_id')->constrained('pinyin_tones')->cascadeOnDelete();
            $table->string('word')->comment('Vocabulary word, e.g., 增加');
            $table->string('pinyin')->comment('Pinyin of the word, e.g., zēng jiā');
            $table->string('meaning')->nullable()->comment('Meaning of the word');
            $table->string('level')->nullable()->comment('HSK level, e.g., HSK4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinyin_words');
    }
};
