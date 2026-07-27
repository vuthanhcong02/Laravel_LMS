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
        Schema::create('pinyin_finals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->collation('utf8mb4_bin')->unique()->comment('Final name, e.g., a, o, e, i, u, ü');
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
        Schema::dropIfExists('pinyin_finals');
    }
};
