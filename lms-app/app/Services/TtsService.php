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
            File::makeDirectory($this->storagePath, 0775, true);
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

        // Resolve edge-tts executable command safely
        $cmd = $this->resolveCommand($voice, $rate, $cleanText, $filePath);

        $process = new Process($cmd);
        $process->setTimeout(15);
        $process->run();

        if (!$process->isSuccessful() || !File::exists($filePath) || File::size($filePath) === 0) {
            $errorOutput = $process->getErrorOutput();
            Log::error('Edge-TTS execution failed', [
                'text'    => $cleanText,
                'voice'   => $voice,
                'command' => implode(' ', $cmd),
                'error'   => $errorOutput,
                'output'  => $process->getOutput(),
            ]);

            throw new Exception('Failed to generate speech audio via Edge-TTS: ' . ($errorOutput ?: 'Process exited unsuccessfully.'));
        }

        return $filePath;
    }

    /**
     * Resolve the edge-tts command array safely (supports absolute paths & python module fallback).
     *
     * @param string $voice
     * @param string $rate
     * @param string $text
     * @param string $outputFile
     * @return array<int, string>
     */
    protected function resolveCommand(string $voice, string $rate, string $text, string $outputFile): array
    {
        $customBin = config('services.tts.edge_binary');
        $baseCmd = null;

        if (!empty($customBin) && File::exists($customBin)) {
            $baseCmd = [$customBin];
        } else {
            // Check standard binary paths
            $candidatePaths = [
                '/usr/local/bin/edge-tts',
                '/usr/bin/edge-tts',
                '/bin/edge-tts',
                '/opt/homebrew/bin/edge-tts',
            ];

            foreach ($candidatePaths as $candidate) {
                if (File::exists($candidate) && is_executable($candidate)) {
                    $baseCmd = [$candidate];
                    break;
                }
            }
        }

        // If direct binary not located at known paths, check python module or default CLI name
        if (!$baseCmd) {
            $baseCmd = ['edge-tts'];
        }

        return array_merge($baseCmd, [
            '--voice',
            $voice,
            '--rate=' . $rate,
            '--text',
            $text,
            '--write-media',
            $outputFile,
        ]);
    }
}
