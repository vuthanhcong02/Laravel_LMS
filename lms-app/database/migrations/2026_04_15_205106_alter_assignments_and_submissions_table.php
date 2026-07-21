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
        Schema::table('assignments', function (Blueprint $table) {
            $table->foreignId('lesson_id')->nullable()->after('course_id')->constrained('lessons')->onDelete('cascade');
            $table->dropColumn('file_url');
            $table->json('attachments')->nullable()->after('description');
            $table->integer('status')->default(0)->after('due_date')->comment('0: Draft, 1: Published');
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropColumn('file_url');
            $table->json('attachments')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn(['lesson_id', 'attachments', 'status']);
            $table->string('file_url')->nullable();
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropColumn(['attachments', 'status']);
            $table->string('file_url')->nullable();
        });
    }
};
