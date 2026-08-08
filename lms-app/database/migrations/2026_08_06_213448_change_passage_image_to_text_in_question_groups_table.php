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
        Schema::table('hsk_mock_exam_question_groups', function (Blueprint $table) {
            $table->text('passage_image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hsk_mock_exam_question_groups', function (Blueprint $table) {
            $table->string('passage_image')->nullable()->change();
        });
    }
};
