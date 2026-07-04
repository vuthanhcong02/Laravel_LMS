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
        Schema::create('hsk_lesson_grammars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hsk_lesson_id')->constrained('hsk_lessons')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('formula', 255)->nullable();
            $table->text('explanation')->nullable();
            $table->json('examples')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_lesson_grammars');
    }
};
