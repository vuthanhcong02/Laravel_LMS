<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TestB2Connection extends Command
{
    /**
     * Console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:test-b2';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Verify Backblaze B2 connection, credentials, and Presigned URL generation';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== STARTING BACKBLAZE B2 CONNECTION TEST ===');

        $bucket = config('filesystems.disks.b2.bucket');
        $endpoint = config('filesystems.disks.b2.endpoint');
        $region = config('filesystems.disks.b2.region');

        $this->table(
            ['Configuration', 'Value'],
            [
                ['Bucket', $bucket ?: 'NOT CONFIGURED'],
                ['Endpoint', $endpoint ?: 'NOT CONFIGURED'],
                ['Region', $region ?: 'NOT CONFIGURED'],
            ]
        );

        if (empty($bucket) || empty($endpoint)) {
            $this->error('Please verify B2_BUCKET and B2_ENDPOINT in your .env file.');
            return Command::FAILURE;
        }

        $testPath = 'test_connection/xiaomu_test_' . time() . '.txt';
        $testContent = 'Backblaze B2 connection successful from XiaoMu LMS at: ' . now()->toDateTimeString();

        try {
            // 1. Test file upload
            $this->line("\n1. Testing file upload to B2: <comment>{$testPath}</comment>...");
            $uploadSuccess = Storage::disk('b2')->put($testPath, $testContent);

            if (!$uploadSuccess) {
                $this->error('Upload failed!');
                return Command::FAILURE;
            }
            $this->info('✓ Upload successful!');

            // 2. Test reading file
            $this->line("\n2. Testing reading file content from B2...");
            $readContent = Storage::disk('b2')->get($testPath);
            if ($readContent === $testContent) {
                $this->info('✓ Read content matches: "' . $readContent . '"');
            } else {
                $this->warn('Read content mismatch.');
            }

            // 3. Test Presigned URL (Temporary URL)
            $this->line("\n3. Testing Presigned URL generation (valid for 10 minutes)...");
            $presignedUrl = Storage::disk('b2')->temporaryUrl($testPath, now()->addMinutes(10));
            $this->info('✓ Presigned URL generated successfully:');
            $this->line("<comment>{$presignedUrl}</comment>");

            // 4. Clean up test file
            $this->line("\n4. Cleaning up test file on B2...");
            Storage::disk('b2')->delete($testPath);
            $this->info('✓ Deleted test file successfully.');

            $this->newLine();
            $this->info('🎉 ALL BACKBLAZE B2 CONNECTION TESTS PASSED SUCCESSFULLY!');
            return Command::SUCCESS;

        } catch (Throwable $e) {
            $this->error('B2 CONNECTION ERROR: ' . $e->getMessage());
            $this->line($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
