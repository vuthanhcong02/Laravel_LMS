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
        Schema::create('pinyin_initials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Initial name, e.g., b, p, m, f');
            $table->string('symbol')->nullable()->comment('Phonetic symbol or variant if any');
            $table->integer('order')->default(0)->comment('Sorting order on Grid');
            $table->string('description')->nullable()->comment('Pronunciation description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinyin_initials');
    }
};
