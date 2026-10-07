<?php

namespace App\Console\Commands;

use App\Services\HanziImportService;
use Illuminate\Console\Command;

class ImportHanziDictionaryCommand extends Command
{
    /**
     * Tên lệnh và cú pháp
     *
     * @var string
     */
    protected $signature = 'hanzi:import-dictionary';

    /**
     * Mô tả lệnh
     *
     * @var string
     */
    protected $description = 'Nhập toàn bộ danh mục 214 Bộ thủ Khang Hy và Từ điển Chữ Hán từ dữ liệu JSON vào Database';

    /**
     * Thực thi câu lệnh
     */
    public function handle(HanziImportService $importer): int
    {
        $this->info('🚀 Bắt đầu quá trình đồng bộ Danh mục Bộ thủ & Từ điển Chữ Hán...');

        try {
            $result = $importer->importAll();

            $this->info("✅ Đồng bộ thành công!");
            $this->line(" - Bộ thủ Khang Hy: <comment>{$result['radicals']}</comment> bộ");
            $this->line(" - Chữ Hán chuẩn: <comment>{$result['characters']}</comment> chữ");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('❌ Lỗi trong quá trình nhập dữ liệu: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
