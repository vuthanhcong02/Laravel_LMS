<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add tokens column to practice_sentences table
     */
    public function up(): void
    {
        Schema::table('practice_sentences', function (Blueprint $table) {
            $table->json('tokens')->nullable()->after('words');
        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::table('practice_sentences', function (Blueprint $table) {
            $table->dropColumn('tokens');
        });
    }
};
