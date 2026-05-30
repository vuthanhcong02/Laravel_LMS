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
            if (!Schema::hasColumn('assignments', 'lesson_id')) {
                $table->foreignId('lesson_id')->nullable()->after('course_id')->constrained('lessons')->onDelete('cascade');
            }
            if (Schema::hasColumn('assignments', 'file_url')) {
                $table->dropColumn('file_url');
            }
            if (!Schema::hasColumn('assignments', 'attachments')) {
                $table->json('attachments')->nullable()->after('description');
            }
            if (!Schema::hasColumn('assignments', 'status')) {
                $table->integer('status')->default(0)->after('due_date')->comment('0: Draft, 1: Published');
            }
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('assignment_submissions', 'file_url')) {
                $table->dropColumn('file_url');
            }
            if (!Schema::hasColumn('assignment_submissions', 'attachments')) {
                $table->json('attachments')->nullable()->after('user_id');
            }
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
