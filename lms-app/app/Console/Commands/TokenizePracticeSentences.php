<?php

namespace App\Console\Commands;

use App\Models\PracticeSentence;
use App\Services\ChineseWordTokenizer;
use Illuminate\Console\Command;

class TokenizePracticeSentences extends Command
{
    /**
     * Artisan command signature
     *
     * @var string
     */
    protected $signature = 'sentences:tokenize';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Tokenize Chinese sentences into compound word tokens and store in database';

    /**
     * Execute the command
     */
    public function handle(): int
    {
        $this->info('🚀 Tokenizing sentences into HSK compound words...');

        $totalSentences = PracticeSentence::count();
        $this->line("📊 Found {$totalSentences} sentences to process.");

        $bar = $this->output->createProgressBar($totalSentences);
        $bar->start();

        PracticeSentence::chunk(100, function ($sentences) use ($bar) {
            foreach ($sentences as $sentence) {
                $tokens = ChineseWordTokenizer::tokenize($sentence->hanzi, $sentence->pinyin);
                $sentence->update(['tokens' => $tokens]);
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Sentence tokenization completed successfully!');

        return Command::SUCCESS;
    }
}
