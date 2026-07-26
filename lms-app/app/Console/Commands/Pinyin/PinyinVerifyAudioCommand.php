<?php

namespace App\Console\Commands\Pinyin;

use App\Models\PinyinTone;
use Illuminate\Console\Command;

class PinyinVerifyAudioCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinyin:verify-audio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra xem các file MP3 được tham chiếu trong DB có tồn tại thực sự hay không.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang kiểm tra thư mục audio...');
        $tones = PinyinTone::whereNotNull('audio')->get();
        $missingCount = 0;

        foreach ($tones as $tone) {
            if (!file_exists(public_path('audio/pinyin/' . $tone->audio))) {
                $this->error("Thiếu file audio: {$tone->audio} (Âm: {$tone->display})");
                $missingCount++;
            }
        }

        if ($missingCount > 0) {
            $this->warn("Tổng cộng có {$missingCount} file bị thiếu. Vui lòng chạy lệnh pinyin:sync-audio để tải về.");
        } else {
            $this->info('Toàn bộ file audio trong database đều khả dụng!');
        }
    }
}
