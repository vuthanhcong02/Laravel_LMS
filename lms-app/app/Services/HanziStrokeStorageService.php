<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HanziStrokeStorageService
{
    /**
     * Thư mục gốc lưu trữ vector nét vẽ trong storage/app
     */
    protected string $baseStoragePath = 'hanzi/strokes';

    /**
     * Lấy dữ liệu vector nét vẽ của một chữ Hán
     */
    public function getStrokeData(string $character, ?string $strokeFilePath = null): ?array
    {
        // 1. Nếu có đường dẫn file được lưu cụ thể trong DB
        if ($strokeFilePath && Storage::exists($strokeFilePath)) {
            $jsonContent = Storage::get($strokeFilePath);
            return json_decode($jsonContent, true);
        }

        // 2. Tìm kiếm tự động theo mã Unicode trong toàn bộ kho strokes
        $unicodeHex = $this->getUnicodeHex($character);
        $pattern = storage_path("app/{$this->baseStoragePath}/**/*_{$unicodeHex}.json");
        $files = glob($pattern);

        if (!empty($files) && file_exists($files[0])) {
            $jsonContent = file_get_contents($files[0]);
            return json_decode($jsonContent, true);
        }

        return null;
    }

    /**
     * Lưu trữ hoặc cập nhật dữ liệu vector nét vẽ theo cấu trúc thư mục phân cấp
     *
     * @param string $character Ký tự chữ Hán
     * @param array $strokeData Mảng dữ liệu vector (strokes, medians, stroke_names...)
     * @param int $hskLevel Cấp độ HSK (1 - 6)
     * @param string $radicalCode Mã bộ thủ (nhan, thuy, moc...)
     * @return string Đường dẫn tương đối của file đã lưu trong Storage
     */
    public function saveStrokeData(string $character, array $strokeData, int $hskLevel = 1, string $radicalCode = 'common'): string
    {
        $unicodeHex = $this->getUnicodeHex($character);
        $cleanRadicalCode = preg_replace('/[^a-zA-Z0-9_-]/', '', $radicalCode) ?: 'common';
        $hskDir = "hsk{$hskLevel}";
        
        // Cấu trúc: hanzi/strokes/characters/hsk1/bo_nhan/xiu_U+4F11.json
        $relativeDir = "{$this->baseStoragePath}/characters/{$hskDir}/bo_{$cleanRadicalCode}";
        $fullDirPath = storage_path("app/{$relativeDir}");

        if (!File::exists($fullDirPath)) {
            File::makeDirectory($fullDirPath, 0755, true);
        }

        $fileName = "{$character}_{$unicodeHex}.json";
        $relativeFilePath = "{$relativeDir}/{$fileName}";
        
        // Bổ sung metadata chuẩn nếu chưa có
        $payload = array_merge([
            'character' => $character,
            'unicode' => $unicodeHex,
            'stroke_count' => count($strokeData['strokes'] ?? []),
            'updated_at' => now()->toIso8601String(),
        ], $strokeData);

        Storage::put($relativeFilePath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $relativeFilePath;
    }

    /**
     * Lưu dữ liệu nét vẽ cho 214 Bộ thủ gốc
     */
    public function saveRadicalStrokeData(string $radical, string $radicalCode, int $strokeCount, array $strokeData): string
    {
        $unicodeHex = $this->getUnicodeHex($radical);
        $strokeGroup = str_pad($strokeCount, 2, '0', STR_PAD_LEFT) . '_net';
        $relativeDir = "{$this->baseStoragePath}/radicals/{$strokeGroup}";
        $fullDirPath = storage_path("app/{$relativeDir}");

        if (!File::exists($fullDirPath)) {
            File::makeDirectory($fullDirPath, 0755, true);
        }

        $fileName = "{$radicalCode}_{$unicodeHex}.json";
        $relativeFilePath = "{$relativeDir}/{$fileName}";

        $payload = array_merge([
            'radical' => $radical,
            'radical_code' => $radicalCode,
            'unicode' => $unicodeHex,
            'stroke_count' => $strokeCount,
            'updated_at' => now()->toIso8601String(),
        ], $strokeData);

        Storage::put($relativeFilePath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $relativeFilePath;
    }

    /**
     * Xóa tệp dữ liệu nét vẽ khi Admin xóa chữ
     */
    public function deleteStrokeData(string $strokeFilePath): bool
    {
        if ($strokeFilePath && Storage::exists($strokeFilePath)) {
            return Storage::delete($strokeFilePath);
        }
        return false;
    }

    /**
     * Chuyển đổi ký tự sang định dạng mã Unicode Hex chuẩn (VD: 休 -> U+4F11)
     */
    public function getUnicodeHex(string $char): string
    {
        $code = mb_ord($char, 'UTF-8');
        return 'U+' . strtoupper(dechex($code));
    }
}
