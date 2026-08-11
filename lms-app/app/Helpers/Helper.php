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
        $html = trim($html ?? '');
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
                        return '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-0.5"><span class="text-base font-black text-slate-900 dark:text-white">' . e($hz) . '</span><rt class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-0.5 select-none">' . e($rt) . '</rt></ruby>';
                    }
                    return $m[0];
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
                    return $html;
                }
            } else {
                return function_exists('hsk_render_pinyin') ? hsk_render_pinyin($html) : $html;
            }
        }

        preg_match_all('/(?:[a-zA-Z]{1,3})?[aeiouüāáǎàēéěèīíǐìōóǒòūúǔùǖǘǚǜAEIOUÜĀÁǍÀĒÉĚÈĪÍǏÌŌÓǑÒŪÚǓÙǕǗǙǛ]+(?:ng|n|r)?/iu', $pinyinStr, $m);
        $validPinyins = $m[0] ?? [];

        $hanziStr = preg_replace('/[ \t\r]+/u', '', $hanziStr);
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
                    $out .= '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-0.5"><span class="text-base font-black text-slate-900 dark:text-white">' . e($char) . '</span><rt class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-0.5 select-none">' . e($validPinyins[$pIdx++]) . '</rt></ruby>';
                } else if (preg_match('/[a-zA-Z0-9]/', $char)) {
                    $out .= '<span class="mx-1 text-base font-black text-slate-900 dark:text-white">' . e($char) . '</span>';
                } else {
                    $out .= '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-0.5"><span class="text-base font-black text-slate-900 dark:text-white">' . e($char) . '</span><rt class="text-[11px] font-bold text-transparent mb-0.5 select-none">.</rt></ruby>';
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

        // Separate segments marked with manual overrides like {háng|行} or {hang2|行}
        // For flexibility, we first normalize the text.
        
        $chars = mb_str_split($text);
        
        // Extract all Chinese characters to evaluate their pinyin in context (for polyphones)
        $chineseChars = '';
        foreach ($chars as $char) {
            if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $char)) {
                $chineseChars .= $char;
            }
        }
        $validPinyins = Pinyin::sentence($chineseChars)->toArray();
        $pIdx = 0;

        $html = '<div class="inline-flex flex-wrap items-end gap-x-[1px] gap-y-1 align-bottom">';
        
        foreach ($chars as $char) {
            if ($char === "\n") {
                $html .= '<div class="w-full h-0 basis-full my-1"></div>';
            } elseif (preg_match('/[\x{4e00}-\x{9fa5}]/u', $char)) {
                $py = $validPinyins[$pIdx++] ?? (string) Pinyin::sentence($char) ?? '';
                $html .= '<ruby class="inline-flex flex-col-reverse items-center justify-end leading-none mx-[1px]"><span class="text-base font-black text-slate-900 dark:text-white">' . e($char) . '</span><rt class="text-[11px] font-bold text-slate-500 dark:text-slate-400 mb-0.5 select-none">' . e($py) . '</rt></ruby>';
            } elseif (trim($char) === '') {
                $html .= '<span class="mx-1"> </span>';
            } else {
                $html .= '<span class="text-base font-bold text-slate-900 dark:text-white mt-auto self-end mb-[2px]">' . e($char) . '</span>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}
