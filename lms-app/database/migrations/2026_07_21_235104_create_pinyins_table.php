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
        Schema::create('pinyins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('initial_id')->nullable()->constrained('pinyin_initials')->nullOnDelete()->comment('Initial (nullable for zero initial)');
            $table->foreignId('final_id')->nullable()->constrained('pinyin_finals')->nullOnDelete()->comment('Final');
            $table->string('full')->unique()->comment('Full pinyin without tone, e.g., zeng');
            $table->string('ipa')->nullable()->comment('IPA phonetic transcription');
            $table->string('vietnamese_pronunciation')->nullable()->comment('Equivalent Vietnamese pronunciation');
            $table->string('description')->nullable()->comment('General description');
            $table->boolean('is_valid')->default(true)->comment('Indicates if this syllable actually exists in Chinese');
            $table->boolean('is_special')->default(false)->comment('Indicates special syllable (e.g., er, o, a)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinyins');
    }
};
