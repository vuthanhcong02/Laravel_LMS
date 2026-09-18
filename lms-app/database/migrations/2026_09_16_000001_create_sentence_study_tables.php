<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations for sentence study tables
     */
    public function up(): void
    {
        // 1. Topics table by HSK level
        Schema::create('sentence_topics', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 150)->unique();
            $table->unsignedTinyInteger('level')->index(); // 1..9 (HSK1..HSK9)
            $table->string('title'); // English title (e.g. A Rainy Day)
            $table->string('title_vi')->nullable(); // Vietnamese title
            $table->string('hanzi')->nullable(); // Topic Chinese title
            $table->string('pinyin')->nullable(); // Topic Pinyin
            $table->unsignedInteger('total_sentences')->default(0);
            $table->timestamps();
        });

        // 2. Practice sentences table
        Schema::create('practice_sentences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('sentence_topics')->cascadeOnDelete();
            $table->unsignedInteger('order_index')->default(0)->index();
            $table->text('hanzi'); // Standard Chinese text
            $table->text('pinyin')->nullable(); // Pinyin transcription
            $table->text('meaning')->nullable(); // English translation
            $table->text('meaning_vi')->nullable(); // Vietnamese translation
            $table->string('audio_path')->nullable(); // Relative storage path for audio MP3
            $table->unsignedInteger('duration')->default(0); // Audio duration in ms
            $table->json('words')->nullable(); // Speech karaoke timestamps [{hanzi, start, end}]
            $table->timestamps();
        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_sentences');
        Schema::dropIfExists('sentence_topics');
    }
};
