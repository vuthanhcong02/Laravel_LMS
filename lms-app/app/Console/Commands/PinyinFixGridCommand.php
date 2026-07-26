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

        $initialsOrder = ['', 'b', 'p', 'm', 'f', 'd', 't', 'n', 'l', 'z', 'c', 's', 'zh', 'ch', 'sh', 'r', 'j', 'q', 'x', 'g', 'k', 'h'];
        $finalsOrder = [
            'a', 'ai', 'ao', 'an', 'ang',
            'o', 'ong', 'ou',
            'e', 'ei', 'en', 'eng', 'er',
            'i', 'ia', 'iao', 'ie', 'iu', 'ian', 'iang', 'in', 'ing', 'iong',
            'u', 'ua', 'uo', 'ui', 'uai', 'uan', 'un', 'uang', 'ueng',
            'ü', 'üe', 'üan', 'ün'
        ];

        // Exact whitelist from reference standard Pinyin chart (Image 1)
        $allowedPinyins = [
            // - (Zero initial)
            'a', 'ai', 'ao', 'an', 'ang', 'o', 'ou', 'e', 'en', 'eng', 'er',
            'yi', 'you', 'yan', 'yang', 'yin', 'ying', 'yong',
            'wu', 'wa', 'wo', 'wei', 'wai', 'wan', 'wen', 'wang', 'weng',
            'yu', 'yue', 'yuan', 'yun',
            // b
            'ba', 'bai', 'bao', 'ban', 'bang', 'bo', 'bei', 'ben', 'beng', 'bi', 'bie', 'bian', 'bin', 'bing', 'bu',
            // p
            'pa', 'pai', 'pao', 'pan', 'pang', 'po', 'pou', 'pei', 'pen', 'peng', 'pi', 'piao', 'pie', 'pian', 'pin', 'ping', 'pu',
            // m
            'ma', 'mai', 'mao', 'man', 'mang', 'mo', 'mou', 'mei', 'men', 'meng', 'mi', 'miao', 'mie', 'miu', 'mian', 'min', 'ming', 'mu',
            // f
            'fa', 'fan', 'fang', 'fo', 'fou', 'fei', 'fen', 'feng', 'fu',
            // d
            'da', 'dai', 'dao', 'dan', 'dang', 'dong', 'dou', 'de', 'dei', 'deng', 'di', 'diao', 'die', 'diu', 'dian', 'ding', 'du', 'duo', 'dui', 'duan', 'dun',
            // t
            'ta', 'tai', 'tao', 'tan', 'tang', 'tong', 'tou', 'te', 'teng', 'ti', 'tiao', 'tie', 'tian', 'ting', 'tu', 'tuo', 'tui', 'tuan', 'tun',
            // n (note: nun removed as per standard chart)
            'na', 'nai', 'nao', 'nan', 'nang', 'nong', 'nou', 'ne', 'nei', 'nen', 'neng', 'ni', 'niao', 'nie', 'niu', 'nian', 'niang', 'nin', 'ning', 'nu', 'nuo', 'nuan', 'nü', 'nüe',
            // l
            'la', 'lai', 'lao', 'lan', 'lang', 'long', 'lou', 'le', 'lei', 'leng', 'li', 'lia', 'liao', 'lie', 'liu', 'lian', 'liang', 'lin', 'ling', 'lu', 'luo', 'luan', 'lun', 'lü', 'lüe',
            // z
            'za', 'zai', 'zao', 'zan', 'zang', 'zong', 'zou', 'ze', 'zei', 'zen', 'zeng', 'zi', 'zu', 'zuo', 'zui', 'zuan', 'zun',
            // c
            'ca', 'cai', 'cao', 'can', 'cang', 'cong', 'cou', 'ce', 'cen', 'ceng', 'ci', 'cu', 'cuo', 'cui', 'cuan', 'cun',
            // s
            'sa', 'sai', 'sao', 'san', 'sang', 'song', 'sou', 'se', 'sen', 'seng', 'si', 'su', 'suo', 'sui', 'suan', 'sun',
            // zh
            'zha', 'zhai', 'zhao', 'zhan', 'zhang', 'zhong', 'zhou', 'zhe', 'zhei', 'zhen', 'zheng', 'zhi', 'zhu', 'zhua', 'zhuo', 'zhui', 'zhuai', 'zhuan', 'zhun', 'zhuang',
            // ch
            'cha', 'chai', 'chao', 'chan', 'chang', 'chong', 'chou', 'che', 'chen', 'cheng', 'chi', 'chu', 'chua', 'chuo', 'chui', 'chuai', 'chuan', 'chun', 'chuang',
            // sh
            'sha', 'shai', 'shao', 'shan', 'shang', 'shou', 'she', 'shei', 'shen', 'sheng', 'shi', 'shu', 'shua', 'shuo', 'shui', 'shuai', 'shuan', 'shun', 'shuang',
            // r (rua included)
            'rao', 'ran', 'rang', 'rong', 'rou', 're', 'ren', 'reng', 'ri', 'ru', 'rua', 'ruo', 'rui', 'ruan', 'run',
            // j
            'ji', 'jia', 'jiao', 'jie', 'jiu', 'jian', 'jiang', 'jin', 'jing', 'jiong', 'ju', 'jue', 'juan', 'jun',
            // q
            'qi', 'qia', 'qiao', 'qie', 'qiu', 'qian', 'qiang', 'qin', 'qing', 'qiong', 'qu', 'que', 'quan', 'qun',
            // x
            'xi', 'xia', 'xiao', 'xie', 'xiu', 'xian', 'xiang', 'xin', 'xing', 'xiong', 'xu', 'xue', 'xuan', 'xun',
            // g
            'ga', 'gai', 'gao', 'gan', 'gang', 'gong', 'gou', 'ge', 'gei', 'gen', 'geng', 'gu', 'gua', 'guo', 'gui', 'guai', 'guan', 'gun', 'guang',
            // k (kei included)
            'ka', 'kai', 'kao', 'kan', 'kang', 'kong', 'kou', 'ke', 'kei', 'ken', 'keng', 'ku', 'kua', 'kuo', 'kui', 'kuai', 'kuan', 'kun', 'kuang',
            // h
            'ha', 'hai', 'hao', 'han', 'hang', 'hong', 'hou', 'he', 'hei', 'hen', 'heng', 'hu', 'hua', 'huo', 'hui', 'huai', 'huan', 'hun', 'huang',
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

        $zeroMapping = [
            'yi' => 'i', 'ya' => 'ia', 'yao' => 'iao', 'ye' => 'ie', 'you' => 'iu', 'yan' => 'ian', 'yin' => 'in', 'yang' => 'iang', 'ying' => 'ing', 'yong' => 'iong',
            'wu' => 'u', 'wa' => 'ua', 'wo' => 'uo', 'wai' => 'uai', 'wei' => 'ui', 'wan' => 'uan', 'wen' => 'un', 'wang' => 'uang', 'weng' => 'ueng',
            'yu' => 'ü', 'yue' => 'üe', 'yuan' => 'üan', 'yun' => 'ün',
        ];

        // Ensure all allowed Pinyin cells exist in database so the grid displays every cell from the reference chart
        $pinyinMap = [];
        foreach ($allowedPinyins as $full) {
            $iStr = null;
            $fStr = null;

            if (isset($zeroMapping[$full])) {
                $iStr = '';
                $fStr = $zeroMapping[$full];
            } elseif (in_array($full, ['zhi', 'chi', 'shi', 'ri', 'zi', 'ci', 'si'])) {
                $iStr = substr($full, 0, -1);
                $fStr = 'i';
            } elseif (preg_match('/^(j|q|x)(u|ue|uan|un)$/', $full, $m)) {
                $iStr = $m[1];
                $fStr = str_replace('u', 'ü', $m[2]);
            } elseif ($full === 'nü') { $iStr = 'n'; $fStr = 'ü'; }
            elseif ($full === 'nüe') { $iStr = 'n'; $fStr = 'üe'; }
            elseif ($full === 'lü') { $iStr = 'l'; $fStr = 'ü'; }
            elseif ($full === 'lüe') { $iStr = 'l'; $fStr = 'üe'; }
            else {
                foreach (array_reverse($initialsOrder) as $init) {
                    if ($init !== '' && str_starts_with($full, $init)) {
                        $iStr = $init;
                        $fStr = substr($full, strlen($init));
                        break;
                    }
                }
                if ($iStr === null) {
                    $iStr = '';
                    $fStr = $full;
                }
            }

            if (isset($initModels[$iStr]) && isset($finalModels[$fStr])) {
                $p = Pinyin::firstOrCreate([
                    'initial_id' => $initModels[$iStr]->id,
                    'final_id' => $finalModels[$fStr]->id,
                    'full' => $full,
                ]);
                $pinyinMap[$full] = $p;
            }
        }

        $this->info('Đang quy chuẩn lại file Audio...');
        $audioDir = storage_path('app/public/audio/pinyin');
        $files = glob($audioDir . '/*.mp3');

        $count = 0;
        foreach ($files as $file) {
            $filename = basename($file);
            $name = str_replace('.mp3', '', $filename);

            $tone = (int) substr($name, -1);
            $pinyinText = substr($name, 0, -1);

            if ($tone < 1 || $tone > 5 || !is_numeric(substr($name, -1))) continue;

            $display = $pinyinText;

            // Map custom MP3 names
            if (in_array($pinyinText, ['nv', 'nuu'])) { $display = 'nü'; }
            elseif (in_array($pinyinText, ['nve', 'nuue', 'nue'])) { $display = 'nüe'; }
            elseif (in_array($pinyinText, ['lv', 'luu'])) { $display = 'lü'; }
            elseif (in_array($pinyinText, ['lve', 'luue', 'lue'])) { $display = 'lüe'; }

            if (isset($pinyinMap[$display])) {
                $pinyin = $pinyinMap[$display];
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

        if (in_array($word, ['nv', 'nuu'])) { $word = 'nü'; }
        elseif (in_array($word, ['nve', 'nuue', 'nue'])) { $word = 'nüe'; }
        elseif (in_array($word, ['lv', 'luu'])) { $word = 'lü'; }
        elseif (in_array($word, ['lve', 'luue', 'lue'])) { $word = 'lüe'; }
        else {
            $word = str_replace(['v', 'uu'], 'ü', $word);
        }

        $targetVowel = '';
        if (mb_strpos($word, 'a') !== false) {
            $targetVowel = 'a';
        } elseif (mb_strpos($word, 'e') !== false) {
            $targetVowel = 'e';
        } elseif (mb_strpos($word, 'ou') !== false) {
            $targetVowel = 'o';
        } else {
            preg_match_all('/[aeiouü]/u', $word, $matches);
            if (!empty($matches[0])) {
                $targetVowel = end($matches[0]);
            }
        }

        if ($targetVowel && isset($vowels[$targetVowel]) && $num >= 1 && $num <= 4) {
            $mark = $vowels[$targetVowel][$num - 1];
            $pos = mb_strrpos($word, $targetVowel);
            if ($pos !== false) {
                $word = mb_substr($word, 0, $pos) . $mark . mb_substr($word, $pos + mb_strlen($targetVowel));
            }
        }

        return $word;
    }
}
