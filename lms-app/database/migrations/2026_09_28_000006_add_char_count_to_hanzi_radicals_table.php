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
        if (!Schema::hasColumn('hanzi_radicals', 'char_count')) {
            Schema::table('hanzi_radicals', function (Blueprint $table) {
                $table->unsignedSmallInteger('char_count')->default(0)->after('stroke_count')->comment('Tổng số chữ Hán theo thống kê Xiehanzi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('hanzi_radicals', 'char_count')) {
            Schema::table('hanzi_radicals', function (Blueprint $table) {
                $table->dropColumn('char_count');
            });
        }
    }
};
