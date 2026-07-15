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
        Schema::table('hsk_lesson_practice_questions', function (Blueprint $table) {
            $table->text('context')->nullable()->after('question_pinyin');
            $table->json('sub_questions')->nullable()->after('options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hsk_lesson_practice_questions', function (Blueprint $table) {
            $table->dropColumn(['context', 'sub_questions']);
        });
    }
};
