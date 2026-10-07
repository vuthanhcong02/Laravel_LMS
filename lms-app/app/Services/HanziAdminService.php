<?php

namespace App\Services;

use App\Models\HanziCharacter;
use App\Models\HanziComponent;
use App\Models\HanziCompoundWord;
use App\Models\HanziExample;
use App\Models\HanziRadical;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HanziAdminService
{
    protected HanziStrokeStorageService $strokeStorage;

    public function __construct(HanziStrokeStorageService $strokeStorage)
    {
        $this->strokeStorage = $strokeStorage;
    }

    /**
     * Tạo hoặc Cập nhật Bộ thủ
     */
    public function saveRadical(array $data, ?int $id = null): HanziRadical
    {
        $radical = $id ? HanziRadical::findOrFail($id) : new HanziRadical();
        $radical->fill($data);
        $radical->save();

        $this->clearCache();
        return $radical;
    }

    /**
     * Tạo Chữ Hán mới kèm Nét vẽ & Thành phần chiết tự
     */
    public function createCharacter(array $data, ?array $strokeData = null, array $components = [], array $compounds = [], array $examples = []): HanziCharacter
    {
        return DB::transaction(function () use ($data, $strokeData, $components, $compounds, $examples) {
            $char = $data['character'];
            $hsk = (int) ($data['hsk_level'] ?? 1);
            
            $radical = HanziRadical::findOrFail($data['radical_id']);
            $radicalCode = $radical->code;

            // Lưu file vector stroke nếu có
            if ($strokeData) {
                $strokePath = $this->strokeStorage->saveStrokeData($char, $strokeData, $hsk, $radicalCode);
                $data['stroke_file_path'] = $strokePath;
            }

            $character = HanziCharacter::create($data);

            // Lưu thành phần chiết tự
            foreach ($components as $index => $comp) {
                $comp['character_id'] = $character->id;
                $comp['sort_order'] = $comp['sort_order'] ?? ($index + 1);
                HanziComponent::create($comp);
            }

            // Lưu từ ghép
            foreach ($compounds as $index => $cw) {
                $cw['character_id'] = $character->id;
                $cw['sort_order'] = $cw['sort_order'] ?? ($index + 1);
                HanziCompoundWord::create($cw);
            }

            // Lưu câu ví dụ
            foreach ($examples as $index => $ex) {
                $ex['character_id'] = $character->id;
                $ex['sort_order'] = $ex['sort_order'] ?? ($index + 1);
                HanziExample::create($ex);
            }

            $this->clearCache();
            return $character;
        });
    }

    /**
     * Cập nhật Chữ Hán
     */
    public function updateCharacter(int $id, array $data, ?array $strokeData = null): HanziCharacter
    {
        return DB::transaction(function () use ($id, $data, $strokeData) {
            $character = HanziCharacter::findOrFail($id);

            if ($strokeData) {
                $radical = $character->radical;
                $strokePath = $this->strokeStorage->saveStrokeData(
                    $character->character,
                    $strokeData,
                    (int) ($data['hsk_level'] ?? $character->hsk_level),
                    $radical ? $radical->code : 'common'
                );
                $data['stroke_file_path'] = $strokePath;
            }

            $character->update($data);
            $this->clearCache();
            return $character;
        });
    }

    /**
     * Xóa Chữ Hán & dọn dẹp file vector
     */
    public function deleteCharacter(int $id): bool
    {
        $character = HanziCharacter::findOrFail($id);
        if ($character->stroke_file_path) {
            $this->strokeStorage->deleteStrokeData($character->stroke_file_path);
        }
        $result = $character->delete();
        $this->clearCache();
        return (bool) $result;
    }

    /**
     * Bật / Tắt trạng thái xuất bản
     */
    public function togglePublish(int $id): bool
    {
        $character = HanziCharacter::findOrFail($id);
        $character->is_published = !$character->is_published;
        $character->save();

        $this->clearCache();
        return $character->is_published;
    }

    /**
     * Xóa toàn bộ Cache phân hệ Chiết tự
     */
    public function clearCache(): void
    {
        Cache::forget('hanzi:radicals:214:grouped');
    }
}
