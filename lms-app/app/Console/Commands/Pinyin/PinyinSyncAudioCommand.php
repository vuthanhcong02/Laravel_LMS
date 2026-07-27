<?php

namespace App\Console\Commands\Pinyin;

use App\Models\Pinyin;
use App\Models\PinyinFinal;
use App\Models\PinyinInitial;
use App\Models\PinyinTone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PinyinSyncAudioCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinyin:sync-audio {--url= : URL API hoặc nguồn chứa audio}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tải/đồng bộ hàng loạt file MP3 phát âm Pinyin về thư mục public/audio/pinyin/';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kết nối tới Github để lấy danh sách Pinyin Audio...');
        $audioDir = storage_path('app/public/audio/pinyin');
        if (!file_exists($audioDir)) {
            mkdir($audioDir, 0777, true);
        }

        // Get file list from GitHub repo
        $response = Http::withoutVerifying()
            ->withHeaders(['User-Agent' => 'Laravel-Pinyin-App'])
            ->get('https://api.github.com/repos/davinfifield/mp3-chinese-pinyin-sound/git/trees/master?recursive=1');

        if (!$response->successful()) {
            $this->error('Không thể lấy danh sách file từ Github!');
            $this->error('Lý do: ' . $response->body());
            
            // Fallback: If Github API blocks, we can use raw list or token.
            return;
        }

        $tree = $response->json('tree');
        $initialsList = ['zh', 'ch', 'sh', 'b', 'p', 'm', 'f', 'd', 't', 'n', 'l', 'g', 'k', 'h', 'j', 'q', 'x', 'z', 'c', 's', 'r', 'y', 'w'];
        
        $count = 0;
        foreach ($tree as $item) {
            if (isset($item['path']) && str_ends_with($item['path'], '.mp3')) {
                $filename = basename($item['path']); // e.g.: a1.mp3, ba2.mp3
                $name = str_replace('.mp3', '', $filename); // a1
                
                // Get tone
                $tone = (int) substr($name, -1);
                $pinyinText = substr($name, 0, -1); // ba
                if ($tone < 1 || $tone > 5 || !is_numeric(substr($name, -1))) {
                    // If no number at the end (invalid)
                    continue;
                }

                // Split Initial and Final
                $initialStr = null;
                $finalStr = $pinyinText;
                
                foreach ($initialsList as $init) {
                    if (str_starts_with($pinyinText, $init)) {
                        $initialStr = $init;
                        $finalStr = substr($pinyinText, strlen($init));
                        break;
                    }
                }
                
                if (empty($finalStr)) continue;

                // 1. Create Initial
                $initialModel = null;
                if ($initialStr) {
                    $initialModel = PinyinInitial::firstOrCreate(['name' => $initialStr]);
                }

                // 2. Create Final
                $finalModel = PinyinFinal::firstOrCreate(['name' => $finalStr]);

                // 3. Create Pinyin
                $pinyin = Pinyin::firstOrCreate([
                    'full' => $pinyinText,
                ], [
                    'initial_id' => $initialModel ? $initialModel->id : null,
                    'final_id' => $finalModel->id,
                ]);

                // 4. Create PinyinTone
                $pinyinTone = PinyinTone::firstOrCreate([
                    'pinyin_id' => $pinyin->id,
                    'tone' => $tone,
                ], [
                    'display' => $name, // Temporarily store lowercase without tone marks (will format Unicode later)
                    'audio' => $filename,
                ]);

                // 5. Download MP3 file (if not exists)
                $filePath = $audioDir . '/' . $filename;
                if (!file_exists($filePath)) {
                    $rawUrl = 'https://raw.githubusercontent.com/davinfifield/mp3-chinese-pinyin-sound/master/' . $item['path'];
                    $fileData = @file_get_contents($rawUrl);
                    if ($fileData) {
                        file_put_contents($filePath, $fileData);
                    }
                }
                
                $count++;
            }
        }
        
        $this->info("Đã đồng bộ thành công $count file Audio và cấu trúc Pinyin!");
    }
}
