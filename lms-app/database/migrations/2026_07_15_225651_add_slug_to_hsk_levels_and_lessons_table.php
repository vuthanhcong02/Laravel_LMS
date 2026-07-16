<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\HskLevel;
use App\Models\HskLesson;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hsk_levels', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        Schema::table('hsk_lessons', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->unique(['hsk_level_id', 'slug']);
        });

        // Data migration
        $levels = HskLevel::all();
        foreach ($levels as $level) {
            $level->slug = Str::slug($level->title);
            $level->save();
        }

        $lessons = HskLesson::all();
        foreach ($lessons as $lesson) {
            $lesson->slug = Str::slug('bai ' . $lesson->lesson_number . ' ' . $lesson->title . '-' . $lesson->translation);
            $lesson->save();
        }
    }

    public function down(): void
    {
        Schema::table('hsk_levels', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('hsk_lessons', function (Blueprint $table) {
            $table->dropUnique(['hsk_level_id', 'slug']);
            $table->dropColumn('slug');
        });
    }
};
