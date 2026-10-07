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
        Schema::table('hanzi_characters', function (Blueprint $table) {
            if (!Schema::hasColumn('hanzi_characters', 'word_types')) {
                $table->json('word_types')->nullable()->after('meaning')->comment('Các loại từ & cách dùng: Động từ, Danh từ...');
            }
            if (!Schema::hasColumn('hanzi_characters', 'mnemonic_tip')) {
                $table->text('mnemonic_tip')->nullable()->after('mnemonic_poem')->comment('Mẹo nhớ chiết tự thực tế từ Xiehanzi');
            }
            if (!Schema::hasColumn('hanzi_characters', 'sino_vietnamese_mirror')) {
                $table->text('sino_vietnamese_mirror')->nullable()->after('mnemonic_tip')->comment('Gương Hán-Việt, từ ngữ quen thuộc tiếng Việt');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hanzi_characters', function (Blueprint $table) {
            if (Schema::hasColumn('hanzi_characters', 'word_types')) {
                $table->dropColumn('word_types');
            }
            if (Schema::hasColumn('hanzi_characters', 'mnemonic_tip')) {
                $table->dropColumn('mnemonic_tip');
            }
            if (Schema::hasColumn('hanzi_characters', 'sino_vietnamese_mirror')) {
                $table->dropColumn('sino_vietnamese_mirror');
            }
        });
    }
};
