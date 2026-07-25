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
        Schema::create('pinyin_tones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pinyin_id')->constrained('pinyins')->cascadeOnDelete();
            $table->tinyInteger('tone')->comment('Tone from 1-4, and 5 for neutral tone');
            $table->string('display')->comment('Pinyin display with tone mark, e.g., zēng');
            $table->string('audio')->nullable()->comment('Audio file name, e.g., zeng1.mp3');
            $table->timestamps();
            
            $table->unique(['pinyin_id', 'tone']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinyin_tones');
    }
};
