<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ChineseWordTokenizer
{
    /**
     * Cached HSK vocabulary dictionary in RAM
     *
     * @var array<string, bool>|null
     */
    protected static ?array $dictionary = null;

    /**
     * Standard 410 toneless Pinyin syllables
     *
     * @var array<string, bool>|null
     */
    protected static ?array $validPinyins = null;

    /**
     * Load Pinyin dictionary and HSK vocabularies into RAM
     */
    public static function loadDictionary(): void
    {
        if (self::$validPinyins === null) {
            $pinyinList = [
                'a', 'ai', 'an', 'ang', 'ao', 'ba', 'bai', 'ban', 'bang', 'bao', 'bei', 'ben', 'beng', 'bi', 'bian',
                'biao', 'bie', 'bin', 'bing', 'bo', 'bu', 'ca', 'cai', 'can', 'cang', 'cao', 'ce', 'cen', 'ceng',
                'cha', 'chai', 'chan', 'chang', 'chao', 'che', 'chen', 'cheng', 'chi', 'chong', 'chou', 'chu', 'chua',
                'chuai', 'chuan', 'chuang', 'chui', 'chun', 'chuo', 'ci', 'cong', 'cou', 'cu', 'cuan', 'cui', 'cun',
                'cuo', 'da', 'dai', 'dan', 'dang', 'dao', 'de', 'dei', 'deng', 'di', 'dia', 'dian', 'diao', 'die',
                'ding', 'diu', 'dong', 'dou', 'du', 'duan', 'dui', 'dun', 'duo', 'e', 'ei', 'en', 'eng', 'er',
                'fa', 'fan', 'fang', 'fei', 'fen', 'feng', 'fo', 'fou', 'fu', 'ga', 'gai', 'gan', 'gang', 'gao',
                'ge', 'gei', 'gen', 'geng', 'gong', 'gou', 'gu', 'gua', 'guai', 'guan', 'guang', 'gui', 'gun',
                'guo', 'ha', 'hai', 'han', 'hang', 'hao', 'he', 'hei', 'hen', 'heng', 'hong', 'hou', 'hu', 'hua',
                'huai', 'huan', 'huang', 'hui', 'hun', 'huo', 'ji', 'jia', 'jian', 'jiang', 'jiao', 'jie', 'jin',
                'jing', 'jiong', 'jiu', 'ju', 'juan', 'jue', 'jun', 'ka', 'kai', 'kan', 'kang', 'kao', 'ke', 'kei',
                'ken', 'keng', 'kong', 'kou', 'ku', 'kua', 'kuai', 'kuan', 'kuang', 'kui', 'kun', 'kuo', 'la',
                'lai', 'lan', 'lang', 'lao', 'le', 'lei', 'leng', 'li', 'lia', 'lian', 'liang', 'liao', 'lie',
                'lin', 'ling', 'liu', 'lo', 'long', 'lou', 'lu', 'luan', 'lue', 'lun', 'luo', 'lv', 'ma', 'mai',
                'man', 'mang', 'mao', 'me', 'mei', 'men', 'meng', 'mi', 'mian', 'miao', 'mie', 'min', 'ming',
                'miu', 'mo', 'mou', 'mu', 'na', 'nai', 'nan', 'nang', 'nao', 'ne', 'nei', 'nen', 'neng', 'ni',
                'nian', 'niang', 'niao', 'nie', 'nin', 'ning', 'niu', 'nong', 'nou', 'nu', 'nuan', 'nue', 'nuo',
                'nv', 'o', 'ou', 'pa', 'pai', 'pan', 'pang', 'pao', 'pei', 'pen', 'peng', 'pi', 'pian', 'piao',
                'pie', 'pin', 'ping', 'po', 'pou', 'pu', 'qi', 'qia', 'qian', 'qiang', 'qiao', 'qie', 'qin',
                'qing', 'qiong', 'jiu', 'qu', 'quan', 'que', 'qun', 'ran', 'rang', 'rao', 're', 'ren', 'reng',
                'ri', 'rong', 'rou', 'ru', 'ruan', 'rui', 'run', 'ruo', 'sa', 'sai', 'san', 'sang', 'sao', 'se',
                'sen', 'seng', 'sha', 'shai', 'shan', 'shang', 'shao', 'she', 'shei', 'shen', 'sheng', 'shi',
                'shou', 'shu', 'shua', 'shuai', 'shuan', 'shuang', 'shui', 'shun', 'shuo', 'si', 'song', 'sou',
                'su', 'suan', 'sui', 'sun', 'suo', 'ta', 'tai', 'tan', 'tang', 'tao', 'te', 'teng', 'ti', 'tian',
                'tiao', 'tie', 'ting', 'tong', 'tou', 'tu', 'tuan', 'tui', 'tun', 'tuo', 'wa', 'wai', 'wan',
                'wang', 'wei', 'wen', 'weng', 'wo', 'wu', 'xi', 'xia', 'xian', 'xiang', 'xiao', 'xie', 'xin',
                'xing', 'xiong', 'xiu', 'xu', 'xuan', 'xue', 'xun', 'ya', 'yan', 'yang', 'yao', 'ye', 'yi',
                'yin', 'ying', 'yo', 'yong', 'you', 'yu', 'yuan', 'yue', 'yun', 'za', 'zai', 'zan', 'zang',
                'zao', 'ze', 'zei', 'zen', 'zeng', 'zha', 'zhai', 'zhan', 'zhang', 'zhao', 'zhe', 'zhei', 'zhen',
                'zheng', 'zhi', 'zhong', 'zhou', 'zhu', 'zhua', 'zhuai', 'zhuan', 'zhuang', 'zhui', 'zhun',
                'zhuo', 'zi', 'zong', 'zou', 'zu', 'zuan', 'zui', 'zun', 'zuo'
            ];
            self::$validPinyins = array_fill_keys($pinyinList, true);
        }

        if (self::$dictionary === null) {
            self::$dictionary = Cache::rememberForever('hsk_tokenizer_dictionary', function () {
                $dict = [];
                $words = DB::table('hsk_vocabularies')->pluck('word')->toArray();
                foreach ($words as $rawWord) {
                    $cleaned = trim(preg_replace('/[^\p{Han}]/u', '', $rawWord));
                    $length = mb_strlen($cleaned);
                    if ($length >= 2 && $length <= 5) {
                        $dict[$cleaned] = true;
                    }
                }
                $commonNames = [
                    '王明', '李华', '张伟', '小明', '小红', '大卫', '玛丽', '小李', '老王', '小王', '张老师', '王老师', '李老师'
                ];
                foreach ($commonNames as $name) {
                    $dict[$name] = true;
                }
                return $dict;
            });
        }
    }

    /**
     * Remove tones from Pinyin to lowercase Latin characters
     */
    protected static function removeTones(string $text): string
    {
        $toneMap = [
            'ā' => 'a', 'á' => 'a', 'ǎ' => 'a', 'à' => 'a',
            'ē' => 'e', 'é' => 'e', 'ě' => 'e', 'è' => 'e',
            'ī' => 'i', 'í' => 'i', 'ǐ' => 'i', 'ì' => 'i',
            'ō' => 'o', 'ó' => 'o', 'ǒ' => 'o', 'ò' => 'o',
            'ū' => 'u', 'ú' => 'u', 'ǔ' => 'u', 'ù' => 'u',
            'ǖ' => 'v', 'ǘ' => 'v', 'ǚ' => 'v', 'ǜ' => 'v', 'ü' => 'v',
        ];
        return strtr(mb_strtolower($text), $toneMap);
    }

    /**
     * Count number of syllables in a Pinyin word
     */
    protected static function countPinyinSyllables(string $pinyinWord): int
    {
        $raw = self::removeTones($pinyinWord);
        $raw = trim(preg_replace('/[^a-zv]/', '', $raw));
        if ($raw === '') {
            return 0;
        }

        // Handle trailing erhua 'r'
        $erhuaExtra = 0;
        if (str_ends_with($raw, 'r') && $raw !== 'er' && !str_ends_with($raw, 'ar') && !str_ends_with($raw, 'or')) {
            $raw = substr($raw, 0, -1);
            $erhuaExtra = 1;
        }

        $n = strlen($raw);
        $memo = [];

        $parse = function ($start) use (&$parse, &$memo, $n, $raw) {
            if ($start === $n) {
                return [];
            }
            if (isset($memo[$start])) {
                return $memo[$start];
            }

            for ($end = min($n, $start + 6); $end > $start; $end--) {
                $sub = substr($raw, $start, $end - $start);
                if (isset(self::$validPinyins[$sub])) {
                    $rest = $parse($end);
                    if ($rest !== null) {
                        $res = array_merge([$sub], $rest);
                        return $memo[$start] = $res;
                    }
                }
            }

            return $memo[$start] = null;
        };

        $result = $parse(0);
        if ($result === null) {
            preg_match_all('/[aeiouv]/', $raw, $matches);
            return max(1, count($matches[0])) + $erhuaExtra;
        }

        return count($result) + $erhuaExtra;
    }

    /**
     * Tokenize sentence by aligning with Pinyin words
     *
     * @return array<string>|null Returns tokens array if perfectly matched, null on mismatch
     */
    public static function tokenizeByPinyin(string $hanziText, ?string $pinyinText): ?array
    {
        if (empty($pinyinText)) {
            return null;
        }

        self::loadDictionary();

        $cleanHanzi = preg_replace('/[，。！？、；：“”‘’（）《》…,\.!\?;:"\'\(\)\s]/u', '', $hanziText);
        $totalHanziLen = mb_strlen($cleanHanzi);

        // Split pinyin words by whitespace
        $wordsPinyin = preg_split('/\s+/u', trim($pinyinText));
        $tokens = [];
        $hIdx = 0;

        foreach ($wordsPinyin as $pWord) {
            $cleanPWord = trim($pWord, " \t\n\r\0\x0B,.:;!?\"'()[]{}");
            if ($cleanPWord === '') {
                continue;
            }

            $syllables = self::countPinyinSyllables($cleanPWord);
            if ($syllables <= 0) {
                $syllables = 1;
            }

            if ($hIdx + $syllables > $totalHanziLen) {
                return null; // Length mismatch
            }

            $chip = mb_substr($cleanHanzi, $hIdx, $syllables);
            $hIdx += $syllables;
            $tokens[] = $chip;
        }

        // Verify if all characters were matched
        if ($hIdx !== $totalHanziLen) {
            return null;
        }

        // Merge adjacent capitalized proper names (e.g. Wáng Míng -> 王明)
        $tokens = self::mergeProperNames($tokens, $wordsPinyin);

        return $tokens;
    }

    /**
     * Merge adjacent single-character capitalized proper names (e.g. 王 + 明 -> 王明)
     */
    protected static function mergeProperNames(array $tokens, array $wordsPinyin): array
    {
        $cleanedPinyin = [];
        foreach ($wordsPinyin as $p) {
            $cp = trim($p, " \t\n\r\0\x0B,.:;!?\"'()[]{}");
            if ($cp !== '') {
                $cleanedPinyin[] = $cp;
            }
        }

        if (count($tokens) !== count($cleanedPinyin)) {
            return $tokens;
        }

        $merged = [];
        $count = count($tokens);
        $i = 0;

        while ($i < $count) {
            if ($i + 1 < $count 
                && mb_strlen($tokens[$i]) === 1 
                && mb_strlen($tokens[$i + 1]) === 1
                && ctype_upper(substr($cleanedPinyin[$i], 0, 1))
                && ctype_upper(substr($cleanedPinyin[$i + 1], 0, 1))) {
                $merged[] = $tokens[$i] . $tokens[$i + 1];
                $i += 2;
            } else {
                $merged[] = $tokens[$i];
                $i++;
            }
        }

        return $merged;
    }

    /**
     * Main tokenization method combining Pinyin Alignment and HSK dictionary FMM fallback
     *
     * @param string $hanziText Chinese sentence
     * @param string|null $pinyinText Pinyin sentence
     * @return array<string> List of tokenized words
     */
    public static function tokenize(string $hanziText, ?string $pinyinText = null): array
    {
        // 1. Primary priority: Pinyin Syllable Alignment
        if ($pinyinText) {
            $pinyinTokens = self::tokenizeByPinyin($hanziText, $pinyinText);
            if ($pinyinTokens !== null && count($pinyinTokens) > 0) {
                return $pinyinTokens;
            }
        }

        // 2. Fallback: Forward Maximum Matching (FMM) using HSK vocabulary dictionary
        self::loadDictionary();

        $cleanText = preg_replace('/[，。！？、；：“”‘’（）《》…,\.!\?;:"\'\(\)\s]/u', '', $hanziText);
        $totalLen = mb_strlen($cleanText);
        $tokens = [];
        $i = 0;

        while ($i < $totalLen) {
            $matched = false;
            for ($len = min(4, $totalLen - $i); $len >= 2; $len--) {
                $sub = mb_substr($cleanText, $i, $len);
                if (isset(self::$dictionary[$sub])) {
                    $tokens[] = $sub;
                    $i += $len;
                    $matched = true;
                    break;
                }
            }

            if (!$matched) {
                $tokens[] = mb_substr($cleanText, $i, 1);
                $i++;
            }
        }

        return $tokens;
    }
}
