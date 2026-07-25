<?php

namespace App\Console\Commands;

use App\Models\Pinyin;
use App\Models\PinyinFinal;
use App\Models\PinyinInitial;
use App\Models\PinyinTone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PinyinFixGridCommand extends Command
{
    protected $signature = 'pinyin:fix-grid';
    protected $description = 'Sửa lại toàn bộ dữ liệu Pinyin theo chuẩn bảng Pinyin quốc tế (zero-initials, ü, -i, đúng thứ tự)';

    public function handle()
    {
        $this->info('Đang xoá dữ liệu Pinyin cũ...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PinyinTone::truncate();
        Pinyin::truncate();
        PinyinFinal::truncate();
        PinyinInitial::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $initialsOrder = ['b', 'p', 'm', 'f', 'd', 't', 'n', 'l', 'z', 'c', 's', 'zh', 'ch', 'sh', 'r', 'j', 'q', 'x', 'g', 'k', 'h', ''];
        $finalsOrder = [
            'a',
            'o',
            'e',
            '-i',
            'er',
            'ai',
            'ei',
            'ao',
            'ou',
            'an',
            'en',
            'ang',
            'eng',
            'ong',
            'i',
            'ia',
            'iao',
            'ie',
            'iu',
            'ian',
            'in',
            'iang',
            'ing',
            'iong',
            'u',
            'ua',
            'uo',
            'uai',
            'ui',
            'uan',
            'un',
            'uang',
            'ueng',
            'ü',
            'üe',
            'üan',
            'ün'
        ];

        $this->info('Đang khởi tạo Thanh mẫu & Vận mẫu chuẩn...');
        $initModels = [];
        foreach ($initialsOrder as $index => $init) {
            $initModels[$init] = PinyinInitial::create(['name' => $init, 'order' => $index + 1]);
        }
        $finalModels = [];
        foreach ($finalsOrder as $index => $fin) {
            $finalModels[$fin] = PinyinFinal::create(['name' => $fin, 'order' => $index + 1]);
        }

        $this->info('Đang quy chuẩn lại file Audio...');
        $audioDir = storage_path('app/public/audio/pinyin');
        $files = glob($audioDir . '/*.mp3');

        $zeroMapping = [
            'yi' => 'i',
            'ya' => 'ia',
            'yao' => 'iao',
            'ye' => 'ie',
            'you' => 'iu',
            'yan' => 'ian',
            'yin' => 'in',
            'yang' => 'iang',
            'ying' => 'ing',
            'yong' => 'iong',
            'wu' => 'u',
            'wa' => 'ua',
            'wo' => 'uo',
            'wai' => 'uai',
            'wei' => 'ui',
            'wan' => 'uan',
            'wen' => 'un',
            'wang' => 'uang',
            'weng' => 'ueng',
            'yu' => 'ü',
            'yue' => 'üe',
            'yuan' => 'üan',
            'yun' => 'ün',
        ];

        $count = 0;
        foreach ($files as $file) {
            $filename = basename($file);
            $name = str_replace('.mp3', '', $filename);

            $tone = (int) substr($name, -1);
            $pinyinText = substr($name, 0, -1);

            if ($tone < 1 || $tone > 5 || !is_numeric(substr($name, -1))) continue;

            $iStr = null;
            $fStr = null;
            $display = $pinyinText;

            // 1. Check Zero Initials (y, w)
            if (isset($zeroMapping[$pinyinText])) {
                $iStr = '';
                $fStr = $zeroMapping[$pinyinText];
            }
            // 2. Check -i (zhi, chi, shi, ri, zi, ci, si)
            elseif (in_array($pinyinText, ['zhi', 'chi', 'shi', 'ri', 'zi', 'ci', 'si'])) {
                $iStr = substr($pinyinText, 0, -1);
                $fStr = '-i';
            }
            // 3. Check j, q, x + u -> ü
            elseif (preg_match('/^(j|q|x)(u|ue|uan|un)$/', $pinyinText, $matches)) {
                $iStr = $matches[1];
                $fRaw = $matches[2];
                $fStr = str_replace('u', 'ü', $fRaw); // ju -> j + ü
            }
            // 4. Check v -> ü (nv, lv)
            elseif (str_contains($pinyinText, 'v')) {
                $iStr = substr($pinyinText, 0, 1);
                $fStr = str_replace('v', 'ü', substr($pinyinText, 1));
                $display = str_replace('v', 'ü', $pinyinText);
            }
            // 5. Normal
            else {
                foreach (array_reverse($initialsOrder) as $init) {
                    if ($init !== '' && str_starts_with($pinyinText, $init)) {
                        $iStr = $init;
                        $fStr = substr($pinyinText, strlen($init));
                        break;
                    }
                }
                if ($iStr === null) {
                    // If no initial matches, it's a zero initial (e.g., a, o, e, ai, an, ang...)
                    $iStr = '';
                    $fStr = $pinyinText;
                }
            }

            if (!isset($initModels[$iStr]) || !isset($finalModels[$fStr])) {
                continue; // Skip invalid
            }

            $pinyin = Pinyin::firstOrCreate([
                'initial_id' => $initModels[$iStr]->id,
                'final_id' => $finalModels[$fStr]->id,
                'full' => $display,
            ]);

            $displayTone = $this->formatToneDisplay($name);

            PinyinTone::firstOrCreate([
                'pinyin_id' => $pinyin->id,
                'tone' => $tone,
            ], [
                'display' => $displayTone,
                'audio' => $filename,
            ]);
            $count++;
        }

        Cache::forget('pinyin_data');
        $this->info("Đã quy chuẩn thành công $count file âm thanh!");
    }

    private function formatToneDisplay($original)
    {
        if (!preg_match('/[1-5]$/', $original)) {
            return $original;
        }

        $vowels = [
            'a' => ['ā', 'á', 'ǎ', 'à', 'a'],
            'e' => ['ē', 'é', 'ě', 'è', 'e'],
            'o' => ['ō', 'ó', 'ǒ', 'ò', 'o'],
            'i' => ['ī', 'í', 'ǐ', 'ì', 'i'],
            'u' => ['ū', 'ú', 'ǔ', 'ù', 'u'],
            'ü' => ['ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü'],
            'v' => ['ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü']
        ];

        $num = (int)substr($original, -1);
        $word = substr($original, 0, -1);
        $word = str_replace('v', 'ü', $word);

        $targetVowel = '';
        if (strpos($word, 'a') !== false) {
            $targetVowel = 'a';
        } elseif (strpos($word, 'e') !== false) {
            $targetVowel = 'e';
        } elseif (strpos($word, 'ou') !== false) {
            $targetVowel = 'o';
        } else {
            preg_match_all('/[aeiouü]/', $word, $matches);
            if (!empty($matches[0])) {
                $targetVowel = end($matches[0]);
            }
        }

        if ($targetVowel && $num >= 1 && $num <= 4) {
            $mark = $vowels[$targetVowel][$num - 1];
            $pos = strrpos($word, $targetVowel);
            if ($pos !== false) {
                $word = substr_replace($word, $mark, $pos, strlen($targetVowel));
            }
        }

        return $word;
    }
}
