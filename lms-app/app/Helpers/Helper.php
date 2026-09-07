<?php

use Illuminate\Support\Facades\Log;
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
if (! function_exists('_hsk_cache_remember_forever')) {
    /**
     * Wrapper an toàn cho cache()->rememberForever().
     * Fallback về tính toán trực tiếp nếu cache directory bị xóa hoặc không có quyền ghi.
     */
    function _hsk_cache_remember_forever(string $key, callable $callback): string
    {
        try {
            return cache()->rememberForever($key, $callback);
        } catch (\Throwable $e) {
            // Cache directory không tồn tại hoặc không có quyền ghi (thường sau khi clear cache trên production)
            // Fallback về tính toán trực tiếp thay vì crash toàn trang
            Log::warning('[Helper] Cache write failed, computing directly. Key: ' . $key . ' Error: ' . $e->getMessage());
            return call_user_func($callback);
        }
    }
}

if (! function_exists('_hsk_format_ruby_char')) {
    /**
     * Định dạng thẻ ruby chuẩn và tự động dính các dấu câu/ngoặc đi liền phía sau vào cùng một khối nowrap
     * để tránh việc dấu câu (。, ，, ？, ...) bị rớt xuống dòng một mình.
     */
    function _hsk_format_ruby_char(string $hanzi, string $pinyin = '', string $trailingPunct = '', string $fontSizeClass = 'text-sm font-medium'): string
    {
        $rubyHtml = '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-[1px]"><span class="' . $fontSizeClass . ' zh-text text-slate-800 dark:text-slate-100">' . e($hanzi) . '</span>';
        if (!empty($pinyin)) {
            $rubyHtml .= '<rt class="text-[10px] font-normal text-slate-500 dark:text-slate-400 mb-0.5 select-none">' . e($pinyin) . '</rt>';
        }
        $rubyHtml .= '</ruby>';

        if (!empty($trailingPunct)) {
            return '<span class="inline-flex items-end whitespace-nowrap">' . $rubyHtml . '<span class="' . $fontSizeClass . ' text-slate-800 dark:text-slate-100 self-end mb-[2px]">' . e($trailingPunct) . '</span></span>';
        }

        return $rubyHtml;
    }
}

if (! function_exists('renderHskRubyText')) {
    function renderHskRubyText($html, $pinyinStr = '', $hanziStr = '')
    {
        if (empty(trim($html ?? ''))) return '';
        
        $cacheKey = 'hsk_ruby_v4_' . md5(($html ?? '') . ($pinyinStr ?? '') . ($hanziStr ?? ''));
        return _hsk_cache_remember_forever($cacheKey, function () use ($html, $pinyinStr, $hanziStr) {
            $html = trim($html ?? '');
            // Strip dangerous tags to prevent XSS
            $html = strip_tags($html, '<ruby><rt><rp><br>');
            
            if (!empty($html) && str_contains($html, '<ruby')) {
                // If HTML contains ruby tags, parse ruby elements and glue trailing punctuation
                $parts = preg_split('/(<ruby[^>]*>.*?<\/ruby>)/is', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
                $out = '';
                $count = count($parts);
                
                for ($i = 0; $i < $count; $i++) {
                    $part = $parts[$i];
                    if (preg_match('/<ruby[^>]*>(.*?)<\/ruby>/is', $part, $rubyMatch)) {
                        $inner = $rubyMatch[1];
                        $rt = '';
                        if (preg_match('/<rt[^>]*>(.*?)<\/rt>/is', $inner, $rtMatch)) {
                            $rt = trim(strip_tags($rtMatch[1]));
                        }
                        $hz = trim(strip_tags(preg_replace('/<rt[^>]*>.*?<\/rt>/is', '', $inner)));
                        
                        // Lookahead: Glue any trailing punctuation/brackets to this ruby
                        $trailingPunct = '';
                        if ($i + 1 < $count && !str_contains($parts[$i + 1], '<ruby')) {
                            $nextText = $parts[$i + 1];
                            if (preg_match('/^([^\x{4e00}-\x{9fa5}]+)/u', $nextText, $pMatch)) {
                                $fullMatch = $pMatch[1];
                                if (preg_match('/^(.*?[^\s])(\s+)$/us', $fullMatch, $sep)) {
                                    $trailingPunct = $sep[1];
                                    $remSpace = $sep[2];
                                } else {
                                    $trailingPunct = $fullMatch;
                                    $remSpace = '';
                                }
                                $parts[$i + 1] = $remSpace . mb_substr($nextText, mb_strlen($fullMatch));
                            }
                        }
                        
                        if (!empty($hz)) {
                            $out .= _hsk_format_ruby_char($hz, $rt, $trailingPunct);
                        }
                    } else {
                        // Plain text / punctuation / breaks between rubies
                        if (preg_match('/<br\s*\/?>|\n/i', $part)) {
                            $out .= '<div class="w-full h-0 basis-full my-1"></div>';
                        } elseif (trim($part) === '') {
                            $out .= '<span class="mx-1"> </span>';
                        } else {
                            $out .= '<span class="inline-block whitespace-nowrap text-sm font-medium text-slate-800 dark:text-slate-100 self-end mb-[2px]">' . e($part) . '</span>';
                        }
                    }
                }
                
                return '<div class="inline-flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom">' . $out . '</div>';
            }

            // If pinyinStr and hanziStr were provided
            if (!empty($hanziStr) && !empty($pinyinStr)) {
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
                    preg_match_all('/[\x{4e00}-\x{9fa5}]|[^\x{4e00}-\x{9fa5}]+/u', $hanziStr, $tokMatches);
                    $rawTokens = $tokMatches[0] ?? [];
                    $out = '';
                    $pIdx = 0;
                    $tCount = count($rawTokens);
                    
                    for ($ti = 0; $ti < $tCount; $ti++) {
                        $tok = $rawTokens[$ti];
                        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $tok)) {
                            $py = $validPinyins[$pIdx++] ?? '';
                            $trailingPunct = '';
                            if ($ti + 1 < $tCount && !preg_match('/[\x{4e00}-\x{9fa5}]/u', $rawTokens[$ti + 1])) {
                                $nextTok = $rawTokens[$ti + 1];
                                if (preg_match('/^([^\x{4e00}-\x{9fa5}]+)/u', $nextTok, $pMatch)) {
                                    $fullMatch = $pMatch[1];
                                    if (preg_match('/^(.*?[^\s])(\s+)$/us', $fullMatch, $sep)) {
                                        $trailingPunct = $sep[1];
                                        $remSpace = $sep[2];
                                    } else {
                                        $trailingPunct = $fullMatch;
                                        $remSpace = '';
                                    }
                                    $rawTokens[$ti + 1] = $remSpace . mb_substr($nextTok, mb_strlen($fullMatch));
                                }
                            }
                            $out .= _hsk_format_ruby_char($tok, $py, $trailingPunct);
                        } else {
                            if (preg_match('/<br\s*\/?>|\n/i', $tok)) {
                                $out .= '<div class="w-full h-0 basis-full my-1"></div>';
                            } elseif (trim($tok) === '') {
                                $out .= '<span class="mx-1"> </span>';
                            } else {
                                $out .= '<span class="inline-block whitespace-nowrap text-sm font-medium text-slate-800 dark:text-slate-100 self-end mb-[2px]">' . e($tok) . '</span>';
                            }
                        }
                    }
                    return '<div class="inline-flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom">' . $out . '</div>';
                }
            }

            // Fallback for unaligned text or plain text
            if (function_exists('hsk_render_pinyin')) {
                return hsk_render_pinyin($html);
            }
            
            return '<div><div class="text-xs text-slate-500 mb-1 leading-none">' . e($pinyinStr) . '</div><div class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-widest">' . (!empty($hanziStr) ? e($hanziStr) : e($html)) . '</div></div>';
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

        $cacheKey = 'hsk_pinyin_v4_' . md5($text);
        return _hsk_cache_remember_forever($cacheKey, function () use ($text) {
            // Split by <br> tags to prevent parsing HTML tag characters individually
            $lines = preg_split('/<br\s*\/?>|\n/i', $text);
            $renderedLines = [];

            foreach ($lines as $line) {
                if (trim($line) === '') {
                    $renderedLines[] = '';
                    continue;
                }

                // Extract all Chinese characters to evaluate their pinyin in context (for polyphones)
                $chars = mb_str_split($line);
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

                // Tokenize into Chinese chars and non-Chinese chunks
                preg_match_all('/[\x{4e00}-\x{9fa5}]|[^\x{4e00}-\x{9fa5}]+/u', $line, $matches);
                $rawTokens = $matches[0] ?? [];
                $tCount = count($rawTokens);

                $html = '<div class="inline-flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom">';

                for ($ti = 0; $ti < $tCount; $ti++) {
                    $tok = $rawTokens[$ti];
                    if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $tok)) {
                        $py = $validPinyins[$pIdx++] ?? (string) Pinyin::sentence($tok) ?? '';
                        $trailingPunct = '';

                        // Lookahead: If next token has punctuation/symbols, glue to this Chinese character
                        if ($ti + 1 < $tCount && !preg_match('/[\x{4e00}-\x{9fa5}]/u', $rawTokens[$ti + 1])) {
                            $nextTok = $rawTokens[$ti + 1];
                            if (preg_match('/^([^\x{4e00}-\x{9fa5}]+)/u', $nextTok, $pMatch)) {
                                $fullMatch = $pMatch[1];
                                if (preg_match('/^(.*?[^\s])(\s+)$/us', $fullMatch, $sep)) {
                                    $trailingPunct = $sep[1];
                                    $remSpace = $sep[2];
                                } else {
                                    $trailingPunct = $fullMatch;
                                    $remSpace = '';
                                }
                                $rawTokens[$ti + 1] = $remSpace . mb_substr($nextTok, mb_strlen($fullMatch));
                            }
                        }

                        $html .= _hsk_format_ruby_char($tok, $py, $trailingPunct);
                    } else {
                        if (trim($tok) === '') {
                            $html .= '<span class="mx-1"> </span>';
                        } else {
                            $html .= '<span class="inline-block whitespace-nowrap text-sm font-medium text-slate-800 dark:text-slate-100 self-end mb-[2px]">' . e($tok) . '</span>';
                        }
                    }
                }

                $html .= '</div>';
                $renderedLines[] = $html;
            }

            return implode('<br/>', $renderedLines);
        });
    }
}

if (! function_exists('hsk_render_flashcard_ruby')) {
    /**
     * Render Chinese text with aligned Pinyin Ruby text above each Chinese character for Flashcards.
     */
    function hsk_render_flashcard_ruby(?string $text): string
    {
        if (empty(trim($text ?? ''))) return '';

        $cacheKey = 'hsk_flashcard_ruby_v4_' . md5($text);
        return _hsk_cache_remember_forever($cacheKey, function () use ($text) {
            $lines = preg_split('/<br\s*\/?>|\n/i', $text);
            $renderedLines = [];

            foreach ($lines as $line) {
                $trimmedLine = trim($line);
                if ($trimmedLine === '') {
                    $renderedLines[] = '';
                    continue;
                }

                $chars = mb_str_split($trimmedLine);
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

                preg_match_all('/[\x{4e00}-\x{9fa5}]|[^\x{4e00}-\x{9fa5}]+/u', $trimmedLine, $matches);
                $rawTokens = $matches[0] ?? [];
                $tCount = count($rawTokens);

                $html = '<div class="inline-flex flex-wrap items-end gap-x-[1.5px] gap-y-1.5 align-bottom leading-normal">';
                for ($ti = 0; $ti < $tCount; $ti++) {
                    $tok = $rawTokens[$ti];
                    if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $tok)) {
                        $py = $validPinyins[$pIdx++] ?? (string) Pinyin::sentence($tok) ?? '';
                        $trailingPunct = '';

                        if ($ti + 1 < $tCount && !preg_match('/[\x{4e00}-\x{9fa5}]/u', $rawTokens[$ti + 1])) {
                            $nextTok = $rawTokens[$ti + 1];
                            if (preg_match('/^([^\x{4e00}-\x{9fa5}]+)/u', $nextTok, $pMatch)) {
                                $fullMatch = $pMatch[1];
                                if (preg_match('/^(.*?[^\s])(\s+)$/us', $fullMatch, $sep)) {
                                    $trailingPunct = $sep[1];
                                    $remSpace = $sep[2];
                                } else {
                                    $trailingPunct = $fullMatch;
                                    $remSpace = '';
                                }
                                $rawTokens[$ti + 1] = $remSpace . mb_substr($nextTok, mb_strlen($fullMatch));
                            }
                        }

                        $rubyHtml = '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-[1.5px]"><span class="text-sm sm:text-base font-bold zh-text text-slate-800 dark:text-slate-100">' . e($tok) . '</span><rt class="text-[10px] sm:text-[11px] font-semibold text-[#e07a5f] dark:text-[#f4978e] mb-1 select-none tracking-normal">' . e($py) . '</rt></ruby>';
                        if (!empty($trailingPunct)) {
                            $html .= '<span class="inline-flex items-end whitespace-nowrap">' . $rubyHtml . '<span class="text-sm sm:text-base font-bold text-slate-700 dark:text-slate-300 self-end mb-[2px]">' . e($trailingPunct) . '</span></span>';
                        } else {
                            $html .= $rubyHtml;
                        }
                    } else {
                        if (trim($tok) === '') {
                            $html .= '<span class="mx-1"> </span>';
                        } else {
                            $html .= '<span class="inline-block whitespace-nowrap text-sm sm:text-base font-bold text-slate-700 dark:text-slate-300 mt-auto self-end mb-[2px]">' . e($tok) . '</span>';
                        }
                    }
                }
                $html .= '</div>';
                $renderedLines[] = $html;
            }

            return implode("\n", $renderedLines);
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

