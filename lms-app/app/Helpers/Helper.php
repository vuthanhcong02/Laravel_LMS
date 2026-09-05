<?php

use Illuminate\Support\Facades\Storage;
use Overtrue\Pinyin\Pinyin;

if (! function_exists('splitName')) {
    function splitName(string $fullName): array
    {
        $nameParts = explode(' ', trim($fullName));
        $firstName = array_shift($nameParts);
        $lastName = implode(' ', $nameParts);

        return [
            'first_name' => $firstName ?? '',
            'last_name'  => $lastName ?? '',
        ];
    }
}
if (! function_exists('renderHskRubyText')) {
    function renderHskRubyText($html, $pinyinStr = '', $hanziStr = '')
    {
        if (empty(trim($html ?? ''))) return '';
        
        $cacheKey = 'hsk_ruby_' . md5(($html ?? '') . ($pinyinStr ?? '') . ($hanziStr ?? ''));
        return cache()->rememberForever($cacheKey, function () use ($html, $pinyinStr, $hanziStr) {
            $html = trim($html ?? '');
            // Strip dangerous tags to prevent XSS
            $html = strip_tags($html, '<ruby><rt><rp><br>');
            
            if (!empty($html) && str_contains($html, '<ruby')) {
                // If already split into multiple 1-to-1 rubies, format them with modern flex styling
                if (substr_count($html, '<ruby') > 1) {
                    $styled = preg_replace_callback('/<ruby[^>]*>(.*?)<\/ruby>/is', function($m) {
                        $inner = $m[1];
                        $rt = '';
                        if (preg_match('/<rt[^>]*>(.*?)<\/rt>/is', $inner, $rtMatch)) {
                            $rt = trim(strip_tags($rtMatch[1]));
                        }
                        // Extract hanzi by removing <rt> and stripping other tags
                        $hz = trim(strip_tags(preg_replace('/<rt[^>]*>.*?<\/rt>/is', '', $inner)));
                        
                        if (!empty($rt) && !empty($hz)) {
                            return '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-0.5"><span class="text-sm font-medium zh-text text-slate-800 dark:text-slate-100">' . e($hz) . '</span><rt class="text-[10px] font-normal text-slate-500 dark:text-slate-400 mb-0.5 select-none">' . e($rt) . '</rt></ruby>';
                        }
                        return e(strip_tags($m[0]));
                    }, $html);
                    $styled = preg_replace('/<br\s*\/?>/i', '<div class="w-full h-0 basis-full my-1"></div>', $styled);
                    return '<div class="flex flex-wrap items-end gap-x-2 gap-y-1">' . $styled . '</div>';
                }
                
                if (preg_match('/<rt[^>]*>(.*?)<\/rt>/is', $html, $pyM)) {
                    $extractedPinyin = trim(strip_tags($pyM[1]));
                    $cleanHtml = preg_replace('/<rt[^>]*>.*?<\/rt>/is', '', $html);
                    $extractedHanzi = trim(strip_tags($cleanHtml));
                    if (!empty($extractedPinyin) && !empty($extractedHanzi)) {
                        $pinyinStr = $extractedPinyin;
                        $hanziStr = $extractedHanzi;
                    } else {
                        return e(strip_tags($html));
                    }
                } else {
                    return function_exists('hsk_render_pinyin') ? hsk_render_pinyin($html) : e(strip_tags($html));
                }
            }

            preg_match_all('/(?:[a-zA-Z]{1,3})?[aeiouüāáǎàēéěèīíǐìōóǒòūúǔùǖǘǚǜAEIOUÜĀÁǍÀĒÉĚÈĪÍǏÌŌÓǑÒŪÚǓÙǕǗǙǛ]+(?:ng|n|r)?/iu', $pinyinStr, $m);
            $validPinyins = $m[0] ?? [];

            $chars = mb_str_split($hanziStr);

            $chineseCharCount = 0;
            foreach ($chars as $char) {
                if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $char)) {
                    $chineseCharCount++;
                }
            }

            if (count($validPinyins) === $chineseCharCount && $chineseCharCount > 0) {
                $out = '';
                $pIdx = 0;
                foreach ($chars as $i => $char) {
                    if ($char === "\n") {
                        $out .= '<div class="w-full h-0 basis-full my-1"></div>';
                    } else if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $char)) {
                        $out .= '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-[1px]"><span class="text-sm font-medium zh-text text-slate-800 dark:text-slate-100">' . e($char) . '</span><rt class="text-[10px] font-normal text-slate-500 dark:text-slate-400 mb-0.5 select-none">' . e($validPinyins[$pIdx++]) . '</rt></ruby>';
                    } else if (trim($char) === '') {
                        $out .= '<span class="mx-1"> </span>';
                    } else {
                        $out .= '<span class="text-sm font-medium text-slate-800 dark:text-slate-100 mt-auto self-end mb-[2px]">' . e($char) . '</span>';
                    }
                }
                return $out;
            }

            // Fallback for unaligned text or plain text
            if (empty($hanziStr) && !empty($html) && function_exists('hsk_render_pinyin')) {
                return hsk_render_pinyin($html);
            }
            
            $fallbackHtml = '<div><div class="text-xs text-slate-500 mb-1 leading-none">' . e($pinyinStr) . '</div><div class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-widest">' . (!empty($hanziStr) ? e($hanziStr) : e($html)) . '</div></div>';
            return $fallbackHtml;
        });
    }
}

if (! function_exists('hsk_storage_url')) {
    function hsk_storage_url(?string $path): string
    {
        if (empty($path)) return '';
        $path = trim($path);
        if (str_starts_with($path, '/storage/')) {
            return $path;
        }
        return Storage::url($path);
    }
}

if (! function_exists('hsk_render_pinyin')) {
    function hsk_render_pinyin(?string $text): string
    {
        if (empty(trim($text ?? ''))) return '';

        $cacheKey = 'hsk_pinyin_' . md5($text);
        return cache()->rememberForever($cacheKey, function () use ($text) {
            // Split by <br> tags to prevent parsing HTML tag characters individually
            $lines = preg_split('/<br\s*\/?>/i', $text);
            $renderedLines = [];

            foreach ($lines as $line) {
                $chars = mb_str_split($line);
                
                // Extract all Chinese characters to evaluate their pinyin in context (for polyphones)
                $chineseChars = '';
                foreach ($chars as $char) {
                    if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $char)) {
                        $chineseChars .= $char;
                    }
                }
                $validPinyins = [];
                if (!empty($chineseChars)) {
                    $validPinyins = Pinyin::sentence($chineseChars)->toArray();
                }
                $pIdx = 0;

                $html = '<div class="inline-flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom">';
                
                foreach ($chars as $char) {
                    if ($char === "\n") {
                        $html .= '<div class="w-full h-0 basis-full my-1"></div>';
                    } elseif (preg_match('/[\x{4e00}-\x{9fa5}]/u', $char)) {
                        $py = $validPinyins[$pIdx++] ?? (string) Pinyin::sentence($char) ?? '';
                        $html .= '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-[1px]"><span class="text-sm font-medium zh-text text-slate-800 dark:text-slate-100">' . e($char) . '</span><rt class="text-[10px] font-normal text-slate-500 dark:text-slate-400 mb-0.5 select-none">' . e($py) . '</rt></ruby>';
                    } elseif (trim($char) === '') {
                        $html .= '<span class="mx-1"> </span>';
                    } else {
                        $html .= '<span class="text-sm font-medium text-slate-800 dark:text-slate-100 mt-auto self-end mb-[2px]">' . e($char) . '</span>';
                    }
                }
                $html .= '</div>';
                $renderedLines[] = $html;
            }

            return implode('<br/>', $renderedLines);
        });
    }
}

if (! function_exists('hsk_should_show_pinyin')) {
    function hsk_should_show_pinyin($level = null): bool
    {
        if (empty($level) || !isset($level->level_code)) {
            return true;
        }
        $levelNum = (int) str_replace('hsk', '', strtolower($level->level_code));
        return $levelNum < 4;
    }
}

if (! function_exists('pinyin_tone_to_unicode')) {
    /**
     * Convert pinyin with tone numbers (e.g. bian1, mi4, gui4, lv3) to accurate Unicode characters with tone marks (biān, mì, guì, lǚ)
     *
     * @param string|null $pinyin
     * @return string
     */
    function pinyin_tone_to_unicode(?string $pinyin): string
    {
        if (empty($pinyin)) {
            return '';
        }

        $toneMap = [
            'a' => ['ā', 'á', 'ǎ', 'à'], 'A' => ['Ā', 'Á', 'Ǎ', 'À'],
            'e' => ['ē', 'é', 'ě', 'è'], 'E' => ['Ē', 'É', 'Ě', 'È'],
            'i' => ['ī', 'í', 'ǐ', 'ì'], 'I' => ['Ī', 'Í', 'Ǐ', 'Ì'],
            'o' => ['ō', 'ó', 'ǒ', 'ò'], 'O' => ['Ō', 'Ó', 'Ǒ', 'Ò'],
            'u' => ['ū', 'ú', 'ǔ', 'ù'], 'U' => ['Ū', 'Ú', 'Ǔ', 'Ù'],
            'ü' => ['ǖ', 'ǘ', 'ǚ', 'ǜ'], 'Ü' => ['Ǖ', 'Ǘ', 'Ǚ', 'Ǜ'],
        ];

        $str = trim($pinyin);
        // Convert convention for 'ü' (u-umlaut): audio dataset uses 'uu' (nuu -> nü, luu -> lü) and 'v' (nv -> nü, lv -> lü)
        $str = str_replace(['uue', 'uun', 'uu', 'UUE', 'UUN', 'UU'], ['üe', 'ün', 'ü', 'ÜE', 'ÜN', 'Ü'], $str);
        $str = str_replace(['v', 'V'], ['ü', 'Ü'], $str);

        if (!preg_match('/^(.*?)([1-5])$/', $str, $matches)) {
            return $str;
        }

        $base = $matches[1];
        $toneNum = (int) $matches[2] - 1;

        if ($toneNum < 0 || $toneNum > 3) {
            return $base;
        }

        $lowerBase = mb_strtolower($base);

        // Rule 1: If vowel 'a' exists, place tone on 'a'
        $idx = mb_strpos($lowerBase, 'a');
        if ($idx !== false) {
            $char = mb_substr($base, $idx, 1);
            return mb_substr($base, 0, $idx) . $toneMap[$char][$toneNum] . mb_substr($base, $idx + 1);
        }

        // Rule 2: If vowel 'e' exists, place tone on 'e'
        $idx = mb_strpos($lowerBase, 'e');
        if ($idx !== false) {
            $char = mb_substr($base, $idx, 1);
            return mb_substr($base, 0, $idx) . $toneMap[$char][$toneNum] . mb_substr($base, $idx + 1);
        }

        // Rule 3: If 'ou' exists, place tone on 'o'
        $idx = mb_strpos($lowerBase, 'ou');
        if ($idx !== false) {
            $char = mb_substr($base, $idx, 1);
            return mb_substr($base, 0, $idx) . $toneMap[$char][$toneNum] . mb_substr($base, $idx + 1);
        }

        // Rule 4: For other cases (ui, iu, ü...), place tone on the last vowel
        $vowels = ['a', 'e', 'i', 'o', 'u', 'ü'];
        $lastVowelIdx = -1;
        $chars = mb_str_split($base);

        foreach ($chars as $i => $c) {
            if (in_array(mb_strtolower($c), $vowels, true)) {
                $lastVowelIdx = $i;
            }
        }

        if ($lastVowelIdx !== -1) {
            $char = $chars[$lastVowelIdx];
            if (isset($toneMap[$char])) {
                $chars[$lastVowelIdx] = $toneMap[$char][$toneNum];
                return implode('', $chars);
            }
        }

        return $base;
    }
}

