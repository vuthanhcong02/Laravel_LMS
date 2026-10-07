<?php

namespace Database\Seeders;

use App\Services\HanziImportService;
use Illuminate\Database\Seeder;

class HanziDatabaseSeeder extends Seeder
{
    /**
     * Nạp toàn bộ dữ liệu 214 Bộ thủ Khang Hy & Danh mục Chữ Hán vào Database
     */
    public function run(HanziImportService $importer): void
    {
        $importer->importAll();
    }
}
