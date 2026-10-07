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
        // 1. Bảng 214 Bộ thủ Khang Hy
        Schema::create('hanzi_radicals', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique()->comment('Slug định danh bộ thủ: gun, quyet, ren...');
            $table->string('radical', 16)->comment('Ký tự bộ thủ: 丨, 亅, 亻...');
            $table->string('original', 16)->nullable()->comment('Bộ gốc nếu là biến thể: 人, 水, 手...');
            $table->string('sino_vietnamese', 64)->comment('Tên âm Hán - Việt: Cổn, Quyết, Nhân...');
            $table->string('pinyin', 64)->comment('Phiên âm Pinyin: gǔn, jué, rén...');
            $table->unsignedTinyInteger('stroke_count')->default(1)->comment('Số nét: 1 đến 17');
            $table->string('tone', 32)->nullable()->comment('Thanh điệu: 1 ¯, 2 /, 3 V, 4 \\');
            $table->string('meaning', 255)->comment('Ý nghĩa gốc của bộ');
            $table->text('description')->nullable()->comment('Giải thích tự nguyên bộ thủ');
            $table->string('word_type', 64)->nullable()->comment('Từ loại: Danh từ 名词...');
            $table->text('word_type_desc')->nullable()->comment('Giải thích từ loại');
            $table->unsignedSmallInteger('sort_order')->default(0)->comment('Thứ tự sắp xếp');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['stroke_count', 'sort_order']);
        });

        // 2. Bảng Chữ Hán & Chiết tự
        Schema::create('hanzi_characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radical_id')->constrained('hanzi_radicals')->cascadeOnDelete();
            $table->string('character', 16)->comment('Chữ Hán: 休, 串, 国...');
            $table->string('unicode_hex', 16)->nullable()->comment('Mã Unicode: U+4F11');
            $table->string('pinyin', 64)->comment('Phiên âm: xiū, chuàn...');
            $table->string('sino_vietnamese', 64)->comment('Âm Hán - Việt: Hưu, Xuyến...');
            $table->text('meaning')->comment('Nghĩa tiếng Việt');
            $table->unsignedTinyInteger('hsk_level')->default(1)->comment('Cấp độ HSK 1 - 6');
            $table->unsignedTinyInteger('stroke_count')->default(1)->comment('Tổng số nét viết');
            $table->string('tone', 32)->nullable()->comment('Thanh điệu');
            $table->string('formation_type', 64)->nullable()->comment('Loại cấu tạo: Hội ý, Hình thanh, Tượng hình, Chỉ sự');
            $table->text('radical_relation')->nullable()->comment('Mối liên hệ giữa chữ và bộ thủ');
            $table->text('formation_desc')->nullable()->comment('Mô tả chi tiết quá trình ghép chữ');
            $table->text('mnemonic_poem')->nullable()->comment('Thơ lục bát chiết tự ghi nhớ');
            $table->text('traditional_quote')->nullable()->comment('Khẩu quyết chiết tự cổ truyền');
            $table->text('naming_significance')->nullable()->comment('Ý nghĩa khi dùng đặt tên');
            $table->unsignedInteger('popularity_rank')->default(100)->comment('Thứ hạng phổ biến');
            $table->string('stroke_file_path', 255)->nullable()->comment('Đường dẫn file vector json trong storage');
            $table->boolean('is_published')->default(true)->comment('Trạng thái xuất bản');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['character', 'is_published']);
            $table->index(['hsk_level', 'stroke_count']);
            $table->index('pinyin');
            $table->index('sino_vietnamese');
        });

        // 3. Bảng Thành phần chiết tự cấu tạo chữ
        Schema::create('hanzi_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('hanzi_characters')->cascadeOnDelete();
            $table->string('component_char', 16)->comment('Ký tự thành phần: 亻, 木');
            $table->string('component_name', 64)->comment('Tên thành phần: Bộ Nhân, Bộ Mộc');
            $table->string('role', 64)->nullable()->comment('Vai trò: Hình bàng (nghĩa) / Thanh bàng (âm)');
            $table->string('meaning', 255)->nullable()->comment('Ý nghĩa thành phần');
            $table->text('explanation')->nullable()->comment('Giải thích ý nghĩa sự xuất hiện');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['character_id', 'sort_order']);
        });

        // 4. Bảng Từ ghép mở rộng
        Schema::create('hanzi_compound_words', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('hanzi_characters')->cascadeOnDelete();
            $table->string('word', 64)->comment('Từ ghép: 休息');
            $table->string('pinyin', 64)->comment('Pinyin: xiūxi');
            $table->string('sino_vietnamese', 64)->nullable()->comment('Hán - Việt: Hưu tức');
            $table->text('meaning')->comment('Nghĩa tiếng Việt');
            $table->unsignedTinyInteger('hsk_level')->default(1);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['character_id', 'sort_order']);
        });

        // 5. Bảng Câu ví dụ
        Schema::create('hanzi_examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained('hanzi_characters')->cascadeOnDelete();
            $table->text('sentence_zh')->comment('Câu ví dụ chữ Hán');
            $table->text('sentence_pinyin')->comment('Pinyin câu ví dụ');
            $table->text('sentence_vi')->comment('Dịch nghĩa tiếng Việt');
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['character_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hanzi_examples');
        Schema::dropIfExists('hanzi_compound_words');
        Schema::dropIfExists('hanzi_components');
        Schema::dropIfExists('hanzi_characters');
        Schema::dropIfExists('hanzi_radicals');
    }
};
