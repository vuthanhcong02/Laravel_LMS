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
        Schema::create('custom_flashcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained('flashcard_decks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('word', 100);
            $table->string('pinyin', 200);
            $table->text('meaning');
            $table->text('example')->nullable();
            $table->text('example_meaning')->nullable();
            $table->boolean('is_remembered')->default(false);
            $table->timestamp('remembered_at')->nullable();
            $table->timestamps();

            $table->index(['deck_id', 'is_remembered']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_flashcards');
    }
};
