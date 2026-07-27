<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First drop the existing unique index
        Schema::table('pinyins', function (Blueprint $table) {
            $table->dropUnique(['full']);
        });

        // Change the column to use a binary collation and re-add the unique constraint
        Schema::table('pinyins', function (Blueprint $table) {
            $table->string('full')->collation('utf8mb4_bin')->unique()->change();
        });

        Schema::table('pinyin_finals', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('pinyin_finals', function (Blueprint $table) {
            $table->string('name')->collation('utf8mb4_bin')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinyins', function (Blueprint $table) {
            $table->dropUnique(['full']);
        });

        Schema::table('pinyins', function (Blueprint $table) {
            $table->string('full')->collation('utf8mb4_unicode_ci')->unique()->change();
        });

        Schema::table('pinyin_finals', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('pinyin_finals', function (Blueprint $table) {
            $table->string('name')->collation('utf8mb4_unicode_ci')->unique()->change();
        });
    }
};
