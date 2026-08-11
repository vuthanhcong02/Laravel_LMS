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
            $table->string('group_type')->nullable()->after('hsk_mock_exam_section_id')->comment('The ID of the question type from config/hsk_builder.php');
            $table->string('title')->nullable()->after('group_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hsk_mock_exam_question_groups', function (Blueprint $table) {
            $table->dropColumn(['group_type', 'title']);
        });
    }
};
