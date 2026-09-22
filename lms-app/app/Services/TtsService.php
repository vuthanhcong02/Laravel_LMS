<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class TtsService
{
    /**
     * Allowed neural voices for Chinese speech synthesis.
     */
    public const ALLOWED_VOICES = [
        'zh-CN-XiaoxiaoNeural' => 'Xiaoxiao (Female - Warm & Natural)',
        'zh-CN-YunxiNeural'    => 'Yunxi (Male - Lively & Young)',
        'zh-CN-YunjianNeural'  => 'Yunjian (Male - Deep & Formal)',
        'zh-CN-XiaoyiNeural'   => 'Xiaoyi (Female - Gentle)',
    ];

    /**
     * Default voice name.
     */
    public const DEFAULT_VOICE = 'zh-CN-XiaoxiaoNeural';

    /**
     * Storage subdirectory for audio cache.
     */
    protected string $storagePath;

    public function __construct()
    {
        $this->storagePath = storage_path('app/public/tts');
        if (!File::exists($this->storagePath)) {
            File::makeDirectory($this->storagePath, 0755, true);
        }
    }

    /**
     * Synthesize Chinese speech using Edge-TTS with caching.
     *
     * @param string $text
     * @param string $voice
     * @param string $rate
     * @return string Absolute file path to the synthesized MP3 file
     * @throws Exception
     */
    public function synthesize(string $text, string $voice = self::DEFAULT_VOICE, string $rate = '-5%'): string
    {
        $cleanText = trim($text);
        if (empty($cleanText)) {
            throw new Exception('Text to synthesize cannot be empty.');
        }

        // Validate voice against whitelist to ensure safe input
        if (!array_key_exists($voice, self::ALLOWED_VOICES)) {
            $voice = self::DEFAULT_VOICE;
        }

        // Limit text length to prevent resource exhaustion
        if (mb_strlen($cleanText) > 300) {
            $cleanText = mb_substr($cleanText, 0, 300);
        }

        // Cache file identified by hash of voice, rate, and content
        $cacheKey = sha1("{$voice}_{$rate}_{$cleanText}");
        $filePath = $this->storagePath . DIRECTORY_SEPARATOR . "{$cacheKey}.mp3";

        // Return cached audio file if it already exists and is non-empty
        if (File::exists($filePath) && File::size($filePath) > 0) {
            return $filePath;
        }

        // Execute edge-tts CLI safely with parameter array (prevents shell injection)
        $process = new Process([
            'edge-tts',
            '--voice', $voice,
            '--rate=' . $rate,
            '--text', $cleanText,
            '--write-media', $filePath,
        ]);

        $process->setTimeout(15);
        $process->run();

        if (!$process->isSuccessful() || !File::exists($filePath) || File::size($filePath) === 0) {
            Log::error('Edge-TTS execution failed', [
                'text'   => $cleanText,
                'voice'  => $voice,
                'error'  => $process->getErrorOutput(),
                'output' => $process->getOutput(),
            ]);
            throw new Exception('Failed to generate speech audio via Edge-TTS.');
        }

        return $filePath;
    }
}
