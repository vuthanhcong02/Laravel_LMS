<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HskVocabularySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("Bắt đầu chạy Seeder HskVocabulary...");
        $this->command->call('hsk:import-api');
    }
}
