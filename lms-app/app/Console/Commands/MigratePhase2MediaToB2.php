<?php

namespace App\Console\Commands;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\HskMockExam;
use App\Models\HskMockExamOption;
use App\Models\HskMockExamQuestion;
use App\Models\HskMockExamQuestionGroup;
use App\Models\HskMockExamSection;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\SupportMessage;
use App\Services\Media\MediaStorageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigratePhase2MediaToB2 extends Command
{
    /**
     * Console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-lms-media 
                            {--module= : Specify module to migrate (hsk, lessons, quizzes, assignments, support)}
                            {--dry-run : Scan and display files without uploading or modifying DB}';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Migrate LMS media (Lessons, HSK Exams, Quizzes, Assignments, Support attachments) to Backblaze B2';

    /**
     * Execute the console command.
     */
    public function handle(MediaStorageService $mediaService): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $module = strtolower($this->option('module') ?? 'all');

        $this->info("==================================================");
        $this->info("🚀 STARTING LMS MEDIA MIGRATION TO BACKBLAZE B2" . ($module !== 'all' ? " [Module: " . strtoupper($module) . "]" : ""));
        if ($isDryRun) {
            $this->warn("⚠️  DRY-RUN MODE: Scanning only, no files will be written.");
        }
        $this->info("==================================================");

        // 1. Migrate Lesson PDFs and resource notes
        if (in_array($module, ['all', 'lessons', 'lesson'])) {
            $this->migrateLessonFiles($isDryRun);
        }

        // 2. Migrate HSK Mock Exam audios & images
        if (in_array($module, ['all', 'hsk', 'exams', 'mock-exams'])) {
            $this->migrateHskExamAudios($isDryRun);
        }

        // 3. Migrate Quiz global audios and question media
        if (in_array($module, ['all', 'quizzes', 'quiz'])) {
            $this->migrateQuizMedia($isDryRun);
        }

        // 4. Migrate Student Assignment submissions & Teacher feedback audios
        if (in_array($module, ['all', 'assignments', 'assignment'])) {
            $this->migrateAssignmentFiles($isDryRun);
        }

        // 5. Migrate Support ticket attachments
        if (in_array($module, ['all', 'support'])) {
            $this->migrateSupportAttachments($isDryRun);
        }

        $this->newLine();
        $this->info("🎉 MIGRATION FINISHED SUCCESSFULLY!");
        return Command::SUCCESS;
    }

    /**
     * Migrate lesson files (pdf_path and note_file_path) to: courses/course_{course_id}/lessons/lesson_{lesson_id}/...
     */
    protected function migrateLessonFiles(bool $isDryRun): void
    {
        $this->line("\n[1/5] Scanning Lesson files (pdf_path, note_file_path)...");

        $count = 0;
        Lesson::where(function ($q) {
            $q->whereNotNull('pdf_path')->where('pdf_path', '!=', '')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('note_file_path')->where('note_file_path', '!=', '');
              });
        })->chunk(100, function ($lessons) use ($isDryRun, &$count) {
            foreach ($lessons as $lesson) {
                $courseId = $lesson->course_id ?? 0;

                // 1. Migrate pdf_path
                if (!empty($lesson->pdf_path)) {
                    $oldPath = ltrim($lesson->pdf_path, '/');
                    if (str_starts_with($oldPath, 'storage/')) {
                        $oldPath = substr($oldPath, 8);
                    }

                    if (!filter_var($oldPath, FILTER_VALIDATE_URL) && !str_starts_with($oldPath, "courses/course_{$courseId}/lessons/lesson_{$lesson->id}/pdf_")) {
                        $localFullPath = storage_path('app/public/' . $oldPath);
                        if (File::exists($localFullPath)) {
                            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'pdf';
                            $newPath = "courses/course_{$courseId}/lessons/lesson_{$lesson->id}/pdf_" . time() . "_{$lesson->id}.{$ext}";

                            $this->line("  - Lesson #{$lesson->id} PDF: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                            if (!$isDryRun) {
                                try {
                                    Storage::disk('b2')->put($newPath, File::get($localFullPath));
                                    $lesson->pdf_path = $newPath;
                                    $lesson->save();
                                    $count++;
                                } catch (Throwable $e) {
                                    $this->error("    Upload error for Lesson #{$lesson->id} PDF: " . $e->getMessage());
                                }
                            } else {
                                $count++;
                            }
                        }
                    }
                }

                // 2. Migrate note_file_path
                if (!empty($lesson->note_file_path)) {
                    $oldPath = ltrim($lesson->note_file_path, '/');
                    if (str_starts_with($oldPath, 'storage/')) {
                        $oldPath = substr($oldPath, 8);
                    }

                    if (!filter_var($oldPath, FILTER_VALIDATE_URL) && !str_starts_with($oldPath, "courses/course_{$courseId}/lessons/lesson_{$lesson->id}/notes_")) {
                        $localFullPath = storage_path('app/public/' . $oldPath);
                        if (File::exists($localFullPath)) {
                            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'bin';
                            $newPath = "courses/course_{$courseId}/lessons/lesson_{$lesson->id}/notes_" . time() . "_{$lesson->id}.{$ext}";

                            $this->line("  - Lesson #{$lesson->id} Notes: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                            if (!$isDryRun) {
                                try {
                                    Storage::disk('b2')->put($newPath, File::get($localFullPath));
                                    $lesson->note_file_path = $newPath;
                                    $lesson->save();
                                    $count++;
                                } catch (Throwable $e) {
                                    $this->error("    Upload error for Lesson #{$lesson->id} Notes: " . $e->getMessage());
                                }
                            } else {
                                $count++;
                            }
                        }
                    }
                }
            }
        });

        $this->info("✓ Processed {$count} lesson files.");
    }

    /**
     * Migrate HSK Mock Exam audio & image files to B2.
     * Folder structure follows standardized HSK level & exam code hierarchy:
     *   hsk_mock_exams/{HSK_LEVEL}/{EXAM_CODE}/audio/
     *   hsk_mock_exams/{HSK_LEVEL}/{EXAM_CODE}/sections/section_{id}/audio/
     *   hsk_mock_exams/{HSK_LEVEL}/{EXAM_CODE}/sections/section_{id}/groups/group_{id}/audio|image/
     *   .../groups/group_{id}/questions/q_{id}/audio|image/
     *   .../questions/q_{id}/options/opt_{id}.{ext}
     */
    /**
     * Migrate HSK Mock Exam audio & image files directly from local storage folders to B2.
     * Uploads media and updates DB paths for exam, sections, groups, questions, and options.
     */
    protected function migrateHskExamAudios(bool $isDryRun): void
    {
        $this->line("\n[2/5] Scanning HSK Mock Exam media (audio + images)...");

        $count = 0;

        HskMockExam::with([
            'hskLevel',
            'sections.questionGroups.questions.options',
        ])->chunk(20, function ($exams) use ($isDryRun, &$count) {
            foreach ($exams as $exam) {
                $levelCode = strtoupper($exam->hskLevel->level_code ?? 'HSK');

                // Extract real exam code: prioritize title pattern e.g. H10000, H10901, H20901
                $extractedCode = null;
                if (preg_match('/(H\d+)/i', $exam->title, $m)) {
                    $extractedCode = strtoupper($m[1]);
                } elseif (!empty($exam->audio_file) && preg_match('#hsk_mock_exams/(?:[A-Za-z0-9_-]+/)?(H\d+)#i', $exam->audio_file, $m)) {
                    $extractedCode = strtoupper($m[1]);
                }

                $examCode = $extractedCode ?: ($exam->folder_name ?: ('exam_' . $exam->id));
                $examPrefix = "hsk_mock_exams/{$levelCode}/{$examCode}";

                // 1. Locate local media directory for this exam using multiple fallback candidates
                $candidates = array_unique(array_filter([
                    $examCode,
                    $extractedCode,
                    $exam->folder_name,
                    (string) $exam->id,
                    'exam_' . $exam->id,
                ]));

                $localExamDir = null;
                foreach ($candidates as $cand) {
                    $p = storage_path("app/public/hsk_mock_exams/{$cand}");
                    if (File::isDirectory($p)) {
                        $localExamDir = $p;
                        $examCode = $cand;
                        $examPrefix = "hsk_mock_exams/{$levelCode}/{$examCode}";
                        break;
                    }
                    $p = storage_path("app/hsk_mock_exams/{$cand}");
                    if (File::isDirectory($p)) {
                        $localExamDir = $p;
                        $examCode = $cand;
                        $examPrefix = "hsk_mock_exams/{$levelCode}/{$examCode}";
                        break;
                    }
                    $matches = glob(base_path("mock_exam/*/{$cand}"), GLOB_ONLYDIR);
                    if (!empty($matches)) {
                        $localExamDir = $matches[0];
                        $examCode = basename($matches[0]);
                        $examPrefix = "hsk_mock_exams/{$levelCode}/{$examCode}";
                        break;
                    }
                }

                $uploadedAudio = [];
                $uploadedImages = [];

                if ($localExamDir && File::isDirectory($localExamDir)) {
                    $this->line("  Exam #{$exam->id} [{$levelCode}/{$examCode}]: Scanning local <comment>{$localExamDir}</comment>");

                    // 2. Upload audio files from local audio folder to B2
                    $localAudioDir = $localExamDir . '/audio';
                    if (File::isDirectory($localAudioDir)) {
                        foreach (File::files($localAudioDir) as $af) {
                            $fn = $af->getFilename();
                            $targetPath = "{$examPrefix}/audio/{$fn}";
                            if (!$isDryRun) {
                                try {
                                    if (!Storage::disk('b2')->exists($targetPath)) {
                                        Storage::disk('b2')->put($targetPath, File::get($af->getPathname()));
                                    }
                                } catch (Throwable $e) {
                                    $this->error("    Audio upload error [{$fn}]: " . $e->getMessage());
                                }
                            }
                            $uploadedAudio[$fn] = $targetPath;
                            $count++;
                            $this->line("    + Audio: <comment>{$fn}</comment> -> <info>{$targetPath}</info>");
                        }
                    }

                    // 3. Upload images from local images folder to B2
                    $localImagesDir = $localExamDir . '/images';
                    if (File::isDirectory($localImagesDir)) {
                        foreach (File::files($localImagesDir) as $img) {
                            $fn = $img->getFilename();
                            $targetPath = "{$examPrefix}/images/{$fn}";
                            if (!$isDryRun) {
                                try {
                                    if (!Storage::disk('b2')->exists($targetPath)) {
                                        Storage::disk('b2')->put($targetPath, File::get($img->getPathname()));
                                    }
                                } catch (Throwable $e) {
                                    $this->error("    Image upload error [{$fn}]: " . $e->getMessage());
                                }
                            }
                            $uploadedImages[$fn] = $targetPath;
                            $count++;
                        }
                        if (!empty($uploadedImages)) {
                            $this->line("    + Images: Discovered " . count($uploadedImages) . " images in <info>{$examPrefix}/images/</info>");
                        }
                    }
                } else {
                    // Fallback: Check if media was already uploaded to B2 under target prefix
                    try {
                        $b2Imgs = Storage::disk('b2')->files("{$examPrefix}/images");
                        foreach ($b2Imgs as $bp) {
                            $uploadedImages[basename($bp)] = $bp;
                        }
                        $b2Audios = Storage::disk('b2')->files("{$examPrefix}/audio");
                        foreach ($b2Audios as $bp) {
                            $uploadedAudio[basename($bp)] = $bp;
                        }
                    } catch (Throwable $e) {
                        // Ignore B2 list failure
                    }

                    if (!empty($uploadedImages) || !empty($uploadedAudio)) {
                        $this->line("  Exam #{$exam->id} [{$levelCode}/{$examCode}]: Media found on B2 (<info>" . count($uploadedImages) . " images, " . count($uploadedAudio) . " audios</info>)");
                    } else {
                        $this->warn("  ! Local directory not found for Exam #{$exam->id} [{$examCode}] and no files on B2");
                        continue;
                    }
                }

                // 4. Update Database
                $primaryAudioPath = !empty($uploadedAudio) ? reset($uploadedAudio) : null;
                if ($primaryAudioPath && !$isDryRun) {
                    $exam->audio_file = $primaryAudioPath;
                    $exam->save();
                }

                // Check for exam.json in storage, database/data/hsk_mock_exams, or mock_exam directory
                $jsonPath = $this->resolveExamJsonPath($localExamDir ?: '', $examCode, $uploadedImages);

                if ($jsonPath && File::exists($jsonPath)) {
                    $this->line("    + Mapping entities via JSON spec: <info>" . basename($jsonPath) . "</info>");
                    $this->syncExamWithJson($exam, $jsonPath, $uploadedAudio, $uploadedImages, $isDryRun);
                } else {
                    $this->line("    + Mapping entities via heuristic patterns");
                    $this->syncExamByPatterns($exam, $primaryAudioPath, $uploadedAudio, $uploadedImages, $isDryRun);
                }
            }
        });

        // 5. Post-migration database audit to verify all paths are clean
        $staleOptions = HskMockExamOption::where('image', 'like', 'hsk_mock_exams/hsk%')->count();
        $staleQuestions = HskMockExamQuestion::where('image', 'like', 'hsk_mock_exams/hsk%')->count();
        $staleGroups = HskMockExamQuestionGroup::where('passage_image', 'like', 'hsk_mock_exams/hsk%')->count();

        if ($staleOptions > 0 || $staleQuestions > 0 || $staleGroups > 0) {
            $this->warn("\n⚠️  Audit: {$staleOptions} options, {$staleQuestions} questions, {$staleGroups} groups still contain old lowercase paths.");
        } else {
            $this->info("\n✓ Database audit passed: 0 stale paths remaining across all HSK tables.");
        }

        $this->info("✓ Processed {$count} HSK exam media files.");
    }

    /**
     * Resolve the most suitable exam.json file for mapping based on filename overlap.
     */
    protected function resolveExamJsonPath(string $localExamDir, string $examCode, array $uploadedImages): ?string
    {
        // 1. Direct exam.json in storage folder
        $directStorage = $localExamDir . '/exam.json';
        if (File::exists($directStorage)) {
            return $directStorage;
        }

        // 2. Direct code match in database/data/hsk_mock_exams/
        $directData = database_path("data/hsk_mock_exams/{$examCode}.json");
        if (File::exists($directData)) {
            $content = File::get($directData);
            foreach (array_keys($uploadedImages) as $imgFn) {
                if (str_contains($content, $imgFn)) {
                    return $directData;
                }
            }
        }

        // 3. Search all candidate JSON specs for maximum image name intersection
        $allJsons = glob(database_path('data/hsk_mock_exams/*.json'));
        if (empty($allJsons)) {
            $allJsons = glob(base_path('mock_exam/*/*/exam.json'));
        }

        $bestJson = null;
        $maxMatches = 0;

        foreach ($allJsons as $jsonFile) {
            $content = File::get($jsonFile);
            $matches = 0;
            foreach (array_keys($uploadedImages) as $imgFn) {
                if (str_contains($content, $imgFn)) {
                    $matches++;
                }
            }
            if ($matches > $maxMatches) {
                $maxMatches = $matches;
                $bestJson = $jsonFile;
            }
        }

        if ($maxMatches > 0) {
            return $bestJson;
        }

        return File::exists($directData) ? $directData : null;
    }

    /**
     * Map exam DB entities with exact accuracy using original exam.json specification.
     */
    protected function syncExamWithJson(HskMockExam $exam, string $jsonPath, array $uploadedAudio, array $uploadedImages, bool $isDryRun): void
    {
        $data = json_decode(File::get($jsonPath), true);
        if (!$data || empty($data['sections'])) return;

        $sections = $exam->sections->sortBy('order_index')->values();
        $sectionIdx = 0;

        foreach ($data['sections'] as $sData) {
            $section = $sections->get($sectionIdx++);
            if (!$section) break;

            $primaryAudio = !empty($uploadedAudio) ? reset($uploadedAudio) : null;
            if ($primaryAudio && !$isDryRun) {
                $section->audio_file = $primaryAudio;
                $section->save();
            }

            $groups = $section->questionGroups->sortBy('order_index')->values();
            $groupIdx = 0;

            foreach (($sData['parts'] ?? []) as $pData) {
                $group = $groups->get($groupIdx++);
                if (!$group) break;

                // Group passage image (handles single string or array)
                $passageImgs = [];
                if (!empty($pData['images']) && is_array($pData['images'])) {
                    $passageImgs = $pData['images'];
                } elseif (!empty($pData['image'])) {
                    $passageImgs = is_array($pData['image']) ? $pData['image'] : explode(',', $pData['image']);
                }

                if (!empty($passageImgs)) {
                    $b2Imgs = [];
                    foreach ($passageImgs as $imgItem) {
                        $fn = basename(trim($imgItem));
                        if (isset($uploadedImages[$fn])) {
                            $b2Imgs[] = $uploadedImages[$fn];
                        }
                    }
                    if (!empty($b2Imgs) && !$isDryRun) {
                        $group->passage_image = implode(',', $b2Imgs);
                        $group->save();
                    }
                }

                $questions = $group->questions->sortBy('order_index')->values();
                $qIdx = 0;

                foreach (($pData['questions'] ?? []) as $qData) {
                    $question = $questions->get($qIdx++);
                    if (!$question) break;

                    $dirty = false;
                    if (!empty($qData['image'])) {
                        $fn = basename($qData['image']);
                        if (isset($uploadedImages[$fn])) {
                            $question->image = $uploadedImages[$fn];
                            $dirty = true;
                        }
                    } else {
                        // Clear question image if question itself does not have an image
                        if (!empty($question->image)) {
                            $question->image = null;
                            $dirty = true;
                        }
                    }

                    if (!empty($qData['audio'])) {
                        $fn = basename($qData['audio']);
                        if (isset($uploadedAudio[$fn])) {
                            $question->audio_file = $uploadedAudio[$fn];
                            $dirty = true;
                        }
                    }

                    if ($dirty && !$isDryRun) {
                        $question->save();
                    }

                    // Question Options
                    if (!empty($qData['options'])) {
                        $options = $question->options->sortBy('order_index')->values();
                        foreach ($qData['options'] as $oIdx => $optData) {
                            $optModel = $options->get($oIdx);
                            if (!$optModel) break;
                            if (is_array($optData) && !empty($optData['image'])) {
                                $fn = basename($optData['image']);
                                if (isset($uploadedImages[$fn]) && !$isDryRun) {
                                    $optModel->image = $uploadedImages[$fn];
                                    $optModel->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Map exam DB entities using heuristic filename patterns when exam.json is absent.
     */
    protected function syncExamByPatterns(HskMockExam $exam, ?string $primaryAudioPath, array $uploadedAudio, array $uploadedImages, bool $isDryRun): void
    {
        // 1. Assign primary audio to listening section
        if ($primaryAudioPath && !$isDryRun) {
            $listeningSection = $exam->sections->firstWhere('skill_type', 'listening') ?: $exam->sections->first();
            if ($listeningSection) {
                $listeningSection->audio_file = $primaryAudioPath;
                $listeningSection->save();
            }
        }

        $allImgNames = array_keys($uploadedImages);

        // 2. Map group passage images, question images, and option images
        foreach ($exam->sections as $section) {
            foreach ($section->questionGroups as $group) {
                $groupDirty = false;
                $partNum = $group->order_index;
                if (preg_match('/(?:Phần|Part)\s*(\d+)/i', $group->title ?? '', $m)) {
                    $partNum = (int) $m[1];
                }

                // Match passage images: img_p3_*.jpeg, top_img_p4_*.jpeg, p3_*.jpeg (excluding question images like q1_)
                $matchedGroupImgs = [];
                foreach ($allImgNames as $imgName) {
                    if (preg_match('/^q\d+[\._]/i', $imgName)) {
                        continue;
                    }
                    if (preg_match('/(?:^|_)p' . $partNum . '_\d+/i', $imgName) || preg_match('/^top_img_p' . $partNum . '/i', $imgName)) {
                        $matchedGroupImgs[] = $uploadedImages[$imgName];
                    }
                }
                if (!empty($matchedGroupImgs)) {
                    sort($matchedGroupImgs, SORT_NATURAL);
                    if (!$isDryRun) {
                        $group->passage_image = implode(',', $matchedGroupImgs);
                        $groupDirty = true;
                    }
                }
                if ($groupDirty) $group->save();

                // 3. Questions & Options
                foreach ($group->questions as $question) {
                    $qDirty = false;
                    $qNum = $question->order_index;

                    // Match all images for this question: e.g. q7_7.jpeg, q7_8.jpeg, q7.jpeg
                    $matchedQImgs = [];
                    foreach ($allImgNames as $imgName) {
                        if (preg_match('/^q' . $qNum . '\./i', $imgName) || preg_match('/^q' . $qNum . '_\d+/i', $imgName)) {
                            $matchedQImgs[] = $imgName;
                        }
                    }
                    sort($matchedQImgs, SORT_NATURAL);

                    $options = $question->options->sortBy('order_index')->values();

                    if (count($matchedQImgs) > 1) {
                        // MULTIPLE IMAGES -> Question's options (Option A, Option B, Option C)
                        foreach ($matchedQImgs as $idx => $imgFn) {
                            if ($opt = $options->get($idx)) {
                                if (!$isDryRun) {
                                    $opt->image = $uploadedImages[$imgFn];
                                    $opt->save();
                                }
                            }
                        }
                        // Question itself does NOT have an image
                        if (!empty($question->image)) {
                            if (!$isDryRun) {
                                $question->image = null;
                                $qDirty = true;
                            }
                        }
                    } elseif (count($matchedQImgs) === 1) {
                        // SINGLE IMAGE -> Belong to the question itself (e.g. True/False question)
                        if (!$isDryRun) {
                            $question->image = $uploadedImages[$matchedQImgs[0]];
                            $qDirty = true;
                        }
                    } else {
                        // Handle example question in Part 2 if it has option images like img_p3_1, img_p3_2
                        if ($question->is_example && $options->count() > 0) {
                            $exImgs = [];
                            foreach ($allImgNames as $imgName) {
                                if (preg_match('/(?:^|_)p' . $partNum . '_\d+/i', $imgName)) {
                                    $exImgs[] = $imgName;
                                }
                            }
                            sort($exImgs, SORT_NATURAL);
                            if (count($exImgs) === $options->count()) {
                                foreach ($exImgs as $idx => $imgFn) {
                                    if ($opt = $options->get($idx)) {
                                        if (!$isDryRun) {
                                            $opt->image = $uploadedImages[$imgFn];
                                            $opt->save();
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($qDirty) $question->save();
                }
            }
        }
    }

    /**
     * Helper: Migrate an HSK file to B2 using the standardized HSK-level path.
     * Supports both local files and B2-to-B2 server-side reorganization.
     * Returns $newPath on upload success (or dry-run), null on skip/error.
     *
     * @param string $oldRawPath  Original field value from DB
     * @param string $newPrefix   Target B2 prefix without extension (e.g. hsk_mock_exams/HSK1/H10000/audio/H10000)
     * @param bool   $isDryRun
     * @param string $label       Log display label
     */
    protected function migrateHskFile(string $oldRawPath, string $newPrefix, bool $isDryRun, string $label): ?string
    {
        // Skip external URLs (http/https)
        if (filter_var($oldRawPath, FILTER_VALIDATE_URL)) {
            return null;
        }

        $clean = ltrim($oldRawPath, '/');
        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }

        // Determine file extension from current path or local file
        $ext = pathinfo($clean, PATHINFO_EXTENSION);

        // Resolve local file path if available
        $localFullPath = storage_path('app/public/' . $clean);
        if (!File::exists($localFullPath)) {
            $localFullPath = storage_path('app/' . $clean);
        }
        if (!File::exists($localFullPath)) {
            $localFullPath = public_path($clean);
        }

        $hasLocalFile = File::exists($localFullPath);
        if ($hasLocalFile && empty($ext)) {
            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION);
        }
        $ext = $ext ?: 'bin';

        $newPath = "{$newPrefix}.{$ext}";

        // If DB already points to exact target path, nothing to do
        if ($clean === $newPath) {
            return null;
        }

        // Check if file is available locally OR already present on B2
        $hasB2Source = false;
        try {
            $hasB2Source = Storage::disk('b2')->exists($clean);
        } catch (Throwable $e) {
            $hasB2Source = false;
        }

        if (!$hasLocalFile && !$hasB2Source) {
            // Also check if target already exists on B2 (e.g. from previous run)
            try {
                if (Storage::disk('b2')->exists($newPath)) {
                    $this->line("  - {$label} (already on B2): <comment>{$clean}</comment> -> <info>{$newPath}</info>");
                    return $newPath;
                }
            } catch (Throwable $e) {
                // Ignore B2 check error
            }

            $this->warn("  ! Source file not found (local or B2): {$clean}");
            return null;
        }

        $sourceType = $hasLocalFile ? 'local' : 'B2 copy';
        $this->line("  - {$label} [{$sourceType}]: <comment>{$clean}</comment> -> <info>{$newPath}</info>");

        if ($isDryRun) {
            return $newPath; // Return path for counting, skip upload during dry-run
        }

        try {
            if ($hasLocalFile) {
                if (!Storage::disk('b2')->exists($newPath)) {
                    Storage::disk('b2')->put($newPath, File::get($localFullPath));
                }
            } elseif ($hasB2Source) {
                // Server-side copy on B2 from old path to new standardized path
                if (!Storage::disk('b2')->exists($newPath)) {
                    Storage::disk('b2')->copy($clean, $newPath);
                }
                // Clean up old B2 path if different
                if ($clean !== $newPath) {
                    try {
                        Storage::disk('b2')->delete($clean);
                    } catch (Throwable $delEx) {
                        // Ignore delete failure for old key
                    }
                }
            }

            return $newPath;
        } catch (Throwable $e) {
            $this->error("    Upload/copy error [{$label}]: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Migrate Quiz global audio & question images/audio.
     */
    protected function migrateQuizMedia(bool $isDryRun): void
    {
        $this->line("\n[3/5] Scanning Teacher Quiz audios & question media...");

        $count = 0;
        // 1. Quiz global audio
        Quiz::whereNotNull('audio_path')
            ->where('audio_path', '!=', '')
            ->chunk(50, function ($quizzes) use ($isDryRun, &$count) {
                foreach ($quizzes as $quiz) {
                    $oldPath = ltrim($quiz->audio_path, '/');
                    if (str_starts_with($oldPath, 'storage/')) {
                        $oldPath = substr($oldPath, 8);
                    }

                    if (filter_var($oldPath, FILTER_VALIDATE_URL)) {
                        continue;
                    }

                    $localFullPath = storage_path('app/public/' . $oldPath);
                    if (!File::exists($localFullPath)) {
                        continue;
                    }

                    $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'mp3';
                    $newPath = "quizzes/quiz_{$quiz->id}/audio/audio_" . time() . "_{$quiz->id}.{$ext}";

                    $this->line("  - Quiz #{$quiz->id} audio: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                    if (!$isDryRun) {
                        try {
                            Storage::disk('b2')->put($newPath, File::get($localFullPath));
                            $quiz->audio_path = $newPath;
                            $quiz->save();
                            $count++;
                        } catch (Throwable $e) {
                            $this->error("    Upload error for Quiz #{$quiz->id}: " . $e->getMessage());
                        }
                    } else {
                        $count++;
                    }
                }
            });

        // 2. Question images and audios
        Question::where(function ($q) {
            $q->whereNotNull('image_path')->where('image_path', '!=', '')
              ->orWhere(function ($sub) {
                  $sub->whereNotNull('audio_path')->where('audio_path', '!=', '');
              });
        })->chunk(100, function ($questions) use ($isDryRun, &$count) {
            foreach ($questions as $question) {
                // Question Image
                if (!empty($question->image_path)) {
                    $oldPath = ltrim($question->image_path, '/');
                    if (str_starts_with($oldPath, 'storage/')) {
                        $oldPath = substr($oldPath, 8);
                    }

                    $localFullPath = storage_path('app/public/' . $oldPath);
                    if (File::exists($localFullPath) && !filter_var($oldPath, FILTER_VALIDATE_URL) && !str_starts_with($oldPath, 'quizzes/quiz_')) {
                        $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'jpg';
                        $newPath = "quizzes/quiz_{$question->quiz_id}/questions/q_{$question->id}_img." . $ext;

                        $this->line("  - Question #{$question->id} image: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                        if (!$isDryRun) {
                            try {
                                Storage::disk('b2')->put($newPath, File::get($localFullPath));
                                $question->image_path = $newPath;
                                $question->save();
                                $count++;
                            } catch (Throwable $e) {
                                $this->error("    Upload error for Question #{$question->id} image: " . $e->getMessage());
                            }
                        } else {
                            $count++;
                        }
                    }
                }

                // Question Audio
                if (!empty($question->audio_path)) {
                    $oldPath = ltrim($question->audio_path, '/');
                    if (str_starts_with($oldPath, 'storage/')) {
                        $oldPath = substr($oldPath, 8);
                    }

                    $localFullPath = storage_path('app/public/' . $oldPath);
                    if (File::exists($localFullPath) && !filter_var($oldPath, FILTER_VALIDATE_URL) && !str_starts_with($oldPath, 'quizzes/quiz_')) {
                        $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'mp3';
                        $newPath = "quizzes/quiz_{$question->quiz_id}/questions/q_{$question->id}_audio." . $ext;

                        $this->line("  - Question #{$question->id} audio: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                        if (!$isDryRun) {
                            try {
                                Storage::disk('b2')->put($newPath, File::get($localFullPath));
                                $question->audio_path = $newPath;
                                $question->save();
                                $count++;
                            } catch (Throwable $e) {
                                $this->error("    Upload error for Question #{$question->id} audio: " . $e->getMessage());
                            }
                        } else {
                            $count++;
                        }
                    }
                }
            }
        });

        $this->info("✓ Processed {$count} quiz media files.");
    }

    /**
     * Migrate Assignment submissions & Teacher feedback audios.
     */
    protected function migrateAssignmentFiles(bool $isDryRun): void
    {
        $this->line("\n[4/5] Scanning Assignment materials, submissions & Feedback audios...");

        $count = 0;

        // 1. Teacher Assignment materials / attachments
        Assignment::whereNotNull('attachments')->chunk(100, function ($assignments) use ($isDryRun, &$count) {
            foreach ($assignments as $assignment) {
                $attachments = $assignment->attachments ?? [];
                $updated = false;

                if (is_array($attachments) && !empty($attachments)) {
                    foreach ($attachments as $idx => $att) {
                        $oldPath = $att['path'] ?? null;
                        if (!$oldPath || filter_var($oldPath, FILTER_VALIDATE_URL) || str_starts_with($oldPath, 'assignments/assignment_')) {
                            continue;
                        }

                        $localFullPath = storage_path('app/' . $oldPath);
                        if (!File::exists($localFullPath)) {
                            $localFullPath = storage_path('app/public/' . $oldPath);
                        }

                        if (File::exists($localFullPath)) {
                            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'bin';
                            $newPath = "assignments/assignment_{$assignment->id}/materials/mat_" . time() . "_{$idx}.{$ext}";

                            $this->line("  - Assignment #{$assignment->id} material #{$idx}: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                            if (!$isDryRun) {
                                try {
                                    Storage::disk('b2')->put($newPath, File::get($localFullPath));
                                    $attachments[$idx]['path'] = $newPath;
                                    $updated = true;
                                    $count++;
                                } catch (Throwable $e) {
                                    $this->error("    Upload error for Assignment #{$assignment->id} material: " . $e->getMessage());
                                }
                            } else {
                                $count++;
                            }
                        }
                    }

                    if ($updated && !$isDryRun) {
                        $assignment->attachments = $attachments;
                        $assignment->save();
                    }
                }
            }
        });

        // 2. Student submissions & feedback
        AssignmentSubmission::chunk(100, function ($submissions) use ($isDryRun, &$count) {
            foreach ($submissions as $submission) {
                // 1. Attachments array
                $attachments = $submission->attachments ?? [];
                $updated = false;

                if (is_array($attachments) && !empty($attachments)) {
                    foreach ($attachments as $idx => $att) {
                        $oldPath = $att['path'] ?? null;
                        if (!$oldPath || filter_var($oldPath, FILTER_VALIDATE_URL) || str_starts_with($oldPath, 'assignments/assignment_')) {
                            continue;
                        }

                        $localFullPath = storage_path('app/' . $oldPath);
                        if (!File::exists($localFullPath)) {
                            $localFullPath = storage_path('app/public/' . $oldPath);
                        }

                        if (File::exists($localFullPath)) {
                            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'bin';
                            $newPath = "assignments/assignment_{$submission->assignment_id}/submissions/user_{$submission->user_id}/sub_" . time() . "_{$idx}.{$ext}";

                            $this->line("  - Submission #{$submission->id} att #{$idx}: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                            if (!$isDryRun) {
                                try {
                                    Storage::disk('b2')->put($newPath, File::get($localFullPath));
                                    $attachments[$idx]['path'] = $newPath;
                                    $updated = true;
                                    $count++;
                                } catch (Throwable $e) {
                                    $this->error("    Upload error for Submission #{$submission->id} attachment: " . $e->getMessage());
                                }
                            } else {
                                $count++;
                            }
                        }
                    }

                    if ($updated && !$isDryRun) {
                        $submission->attachments = $attachments;
                        $submission->save();
                    }
                }

                // 2. Teacher feedback audio
                if (!empty($submission->teacher_audio_path)) {
                    $oldPath = $submission->teacher_audio_path;
                    if (!filter_var($oldPath, FILTER_VALIDATE_URL) && !str_starts_with($oldPath, 'assignments/assignment_')) {
                        $localFullPath = storage_path('app/' . $oldPath);
                        if (!File::exists($localFullPath)) {
                            $localFullPath = storage_path('app/public/' . $oldPath);
                        }

                        if (File::exists($localFullPath)) {
                            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'mp3';
                            $newPath = "assignments/assignment_{$submission->assignment_id}/feedback/sub_{$submission->id}/feedback_" . time() . ".{$ext}";

                            $this->line("  - Submission #{$submission->id} feedback audio: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                            if (!$isDryRun) {
                                try {
                                    Storage::disk('b2')->put($newPath, File::get($localFullPath));
                                    $submission->teacher_audio_path = $newPath;
                                    $submission->save();
                                    $count++;
                                } catch (Throwable $e) {
                                    $this->error("    Upload error for Submission #{$submission->id} audio: " . $e->getMessage());
                                }
                            } else {
                                $count++;
                            }
                        }
                    }
                }
            }
        });

        $this->info("✓ Processed {$count} assignment submission & feedback files.");
    }

    /**
     * Migrate Support ticket attachments.
     */
    protected function migrateSupportAttachments(bool $isDryRun): void
    {
        $this->line("\n[5/5] Scanning Support ticket attachments...");

        $count = 0;
        SupportMessage::whereNotNull('attachment_path')
            ->where('attachment_path', '!=', '')
            ->chunk(100, function ($messages) use ($isDryRun, &$count) {
                foreach ($messages as $msg) {
                    $oldPath = ltrim($msg->attachment_path, '/');
                    if (str_starts_with($oldPath, 'storage/')) {
                        $oldPath = substr($oldPath, 8);
                    }

                    if (filter_var($oldPath, FILTER_VALIDATE_URL) || str_starts_with($oldPath, 'support/')) {
                        continue;
                    }

                    $localFullPath = storage_path('app/public/' . $oldPath);
                    if (!File::exists($localFullPath)) {
                        $localFullPath = storage_path('app/' . $oldPath);
                    }

                    if (!File::exists($localFullPath)) {
                        continue;
                    }

                    $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'bin';
                    $newPath = "support/ticket_{$msg->ticket_id}/att_" . time() . "_{$msg->id}.{$ext}";

                    $this->line("  - Support Message #{$msg->id}: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

                    if (!$isDryRun) {
                        try {
                            Storage::disk('b2')->put($newPath, File::get($localFullPath));
                            $msg->attachment_path = $newPath;
                            $msg->save();
                            $count++;
                        } catch (Throwable $e) {
                            $this->error("    Upload error for Support Message #{$msg->id}: " . $e->getMessage());
                        }
                    } else {
                        $count++;
                    }
                }
            });

        $this->info("✓ Processed {$count} support ticket attachments.");
    }
}
