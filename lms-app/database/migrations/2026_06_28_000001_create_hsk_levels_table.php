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
        Schema::create('hsk_levels', function (Blueprint $table) {
            $table->id();
            $table->string('level_code', 20)->unique(); // hsk1, hsk2...
            $table->string('title', 50); // HSK 1
            $table->string('subtitle', 150); // Khởi đầu Hán ngữ
            $table->text('description')->nullable();
            $table->string('color', 30)->nullable(); // emerald, cyan...
            $table->unsignedInteger('lessons_count')->default(0);
            $table->unsignedInteger('vocab_count')->default(0);
            $table->string('duration', 50)->nullable(); // 30 giờ
            $table->string('spine_color', 50)->nullable();
            $table->string('cover_bg', 100)->nullable();
            $table->string('number_color', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hsk_levels');
    }
};
