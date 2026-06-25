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
        Schema::create('hsk_vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('word', 100);
            $table->string('pinyin', 200);
            $table->text('meaning')->nullable();
            $table->text('meaning_en')->nullable();
            $table->unsignedTinyInteger('level');
            $table->string('topic', 100)->nullable();
            $table->string('hsk_version', 10)->default('3.0');
            $table->text('example')->nullable();
            $table->text('example_meaning')->nullable();
            $table->timestamps();

            $table->index(['level', 'hsk_version']);
            $table->unique(['word', 'level', 'hsk_version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_vocabularies');
    }
};
