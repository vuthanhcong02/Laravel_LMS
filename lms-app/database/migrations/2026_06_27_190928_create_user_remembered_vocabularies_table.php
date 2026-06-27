<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create user_remembered_vocabularies table.
     */
    public function up(): void
    {
        Schema::create('user_remembered_vocabularies', function (Blueprint $table) {
            $table->id();
            // Foreign key to users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Foreign key to hsk_vocabularies table
            $table->foreignId('hsk_vocabulary_id')
                ->constrained('hsk_vocabularies')
                ->onDelete('cascade');
            $table->timestamps();

            // Ensure each user can only mark a vocabulary as learned once
            $table->unique(['user_id', 'hsk_vocabulary_id'], 'user_vocab_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_remembered_vocabularies');
    }
};
