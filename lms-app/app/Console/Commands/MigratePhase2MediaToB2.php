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
    protected $signature = 'storage:migrate-lms-media {--dry-run : Scan and display files without uploading or modifying DB}';

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

        $this->info("==================================================");
        $this->info("🚀 STARTING PHASE 2 MEDIA MIGRATION TO BACKBLAZE B2");
        if ($isDryRun) {
            $this->warn("⚠️  DRY-RUN MODE: Scanning only, no files will be written.");
        }
        $this->info("==================================================");

        // 1. Migrate Lesson PDFs and resource notes
        $this->migrateLessonFiles($isDryRun);

        // 2. Migrate HSK Mock Exam audios
        $this->migrateHskExamAudios($isDryRun);

        // 3. Migrate Quiz global audios and question media
        $this->migrateQuizMedia($isDryRun);

        // 4. Migrate Student Assignment submissions & Teacher feedback audios
        $this->migrateAssignmentFiles($isDryRun);

        // 5. Migrate Support ticket attachments
        $this->migrateSupportAttachments($isDryRun);

        $this->newLine();
        $this->info("🎉 PHASE 2 MIGRATION FINISHED SUCCESSFULLY!");
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
     * Folder structure follows standardized HSK level hierarchy:
     *   hsk_mock_exams/{levelCode}/exam_{id}/audio/
     *   hsk_mock_exams/{levelCode}/exam_{id}/sections/section_{id}/audio/
     *   hsk_mock_exams/{levelCode}/exam_{id}/sections/section_{id}/groups/group_{id}/audio|image/
     *   .../groups/group_{id}/questions/q_{id}/audio|image/
     *   .../questions/q_{id}/options/opt_{id}.{ext}
     */
    protected function migrateHskExamAudios(bool $isDryRun): void
    {
        $this->line("\n[2/5] Scanning HSK Mock Exam media (audio + images)...");

        $count = 0;

        // Eager load entire relationship tree to obtain level_code and prevent N+1 queries
        HskMockExam::with([
            'hskLevel',
            'sections.questionGroups.questions.options',
        ])->chunk(20, function ($exams) use ($isDryRun, &$count) {
            foreach ($exams as $exam) {
                // E.g.: hsk3, hsk4, hsk6
                $levelCode = strtolower($exam->hskLevel->level_code ?? 'hsk0');
                $examPrefix = "hsk_mock_exams/{$levelCode}/exam_{$exam->id}";

                // --- 1. Exam global audio ---
                if (!empty($exam->audio_file)) {
                    $newPath = $this->migrateHskFile(
                        $exam->audio_file,
                        "{$examPrefix}/audio/exam_{$exam->id}",
                        $isDryRun,
                        "Exam #{$exam->id} [{$levelCode}] audio"
                    );
                    if ($newPath) {
                        if (!$isDryRun) { $exam->audio_file = $newPath; $exam->save(); }
                        $count++;
                    }
                }

                // --- 2. Sections ---
                foreach ($exam->sections as $section) {
                    $sectionPrefix = "{$examPrefix}/sections/section_{$section->id}";

                    if (!empty($section->audio_file)) {
                        $newPath = $this->migrateHskFile(
                            $section->audio_file,
                            "{$sectionPrefix}/audio/section_{$section->id}",
                            $isDryRun,
                            "Section #{$section->id} [{$levelCode}] audio"
                        );
                        if ($newPath) {
                            if (!$isDryRun) { $section->audio_file = $newPath; $section->save(); }
                            $count++;
                        }
                    }

                    // --- 3. QuestionGroups ---
                    foreach ($section->questionGroups as $group) {
                        $groupPrefix = "{$sectionPrefix}/groups/group_{$group->id}";
                        $groupDirty = false;

                        // 3.1 Passage Audio
                        if (!empty($group->passage_audio)) {
                            $newPath = $this->migrateHskFile(
                                $group->passage_audio,
                                "{$groupPrefix}/audio/group_{$group->id}",
                                $isDryRun,
                                "Group #{$group->id} [{$levelCode}] audio"
                            );
                            if ($newPath) {
                                if (!$isDryRun) { $group->passage_audio = $newPath; $groupDirty = true; }
                                $count++;
                            }
                        }

                        // 3.2 Passage Image(s) - supports single string or comma-separated list
                        if (!empty($group->passage_image)) {
                            $rawImages = explode(',', $group->passage_image);
                            $migratedList = [];
                            $hasChange = false;

                            foreach ($rawImages as $idx => $rawImg) {
                                $rawImgClean = trim($rawImg, " \t\n\r\0\x0B\"'[]");
                                if (empty($rawImgClean)) continue;

                                $suffix = count($rawImages) > 1 ? "_{$idx}" : "";
                                $newPath = $this->migrateHskFile(
                                    $rawImgClean,
                                    "{$groupPrefix}/image/group_{$group->id}{$suffix}",
                                    $isDryRun,
                                    "Group #{$group->id} [{$levelCode}] image" . (count($rawImages) > 1 ? " #" . ($idx + 1) : "")
                                );

                                if ($newPath) {
                                    $migratedList[] = $newPath;
                                    $hasChange = true;
                                    $count++;
                                } else {
                                    $migratedList[] = $rawImgClean;
                                }
                            }

                            if ($hasChange && !$isDryRun) {
                                $group->passage_image = implode(',', $migratedList);
                                $groupDirty = true;
                            }
                        }

                        if ($groupDirty) $group->save();

                        // --- 4. Questions ---
                        foreach ($group->questions as $question) {
                            $questionPrefix = "{$groupPrefix}/questions/q_{$question->id}";
                            $questionDirty = false;

                            foreach (['image' => 'image', 'audio_file' => 'audio'] as $field => $type) {
                                if (empty($question->$field)) continue;

                                $newPath = $this->migrateHskFile(
                                    $question->$field,
                                    "{$questionPrefix}/{$type}/q_{$question->id}",
                                    $isDryRun,
                                    "Question #{$question->id} [{$levelCode}] {$type}"
                                );
                                if ($newPath) {
                                    if (!$isDryRun) { $question->$field = $newPath; $questionDirty = true; }
                                    $count++;
                                }
                            }
                            if ($questionDirty) $question->save();

                            // --- 5. Options ---
                            foreach ($question->options as $option) {
                                if (empty($option->image)) continue;

                                $newPath = $this->migrateHskFile(
                                    $option->image,
                                    "{$questionPrefix}/options/opt_{$option->id}",
                                    $isDryRun,
                                    "Option #{$option->id} [{$levelCode}] image"
                                );
                                if ($newPath) {
                                    if (!$isDryRun) { $option->image = $newPath; $option->save(); }
                                    $count++;
                                }
                            }
                        }
                    }
                }
            }
        });

        $this->info("✓ Processed {$count} HSK exam media files.");
    }

    /**
     * Helper: Migrate an HSK file to B2 using the standardized HSK-level path.
     * Returns $newPath on upload success (or dry-run), null on skip/error.
     *
     * @param string $oldRawPath  Original field value from DB
     * @param string $newPrefix   Target B2 prefix without extension (e.g. hsk_mock_exams/hsk3/exam_1/audio/exam_1)
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

        // Already migrated using standardized pattern (path begins with hsk_mock_exams/hsk[0-9a-z_]*)
        if (preg_match('#^hsk_mock_exams/hsk[a-z0-9_-]*/#i', $clean)) {
            return null;
        }

        // Resolve local file path: check public storage, app storage, then public_path
        $localFullPath = storage_path('app/public/' . $clean);
        if (!File::exists($localFullPath)) {
            $localFullPath = storage_path('app/' . $clean);
        }
        if (!File::exists($localFullPath)) {
            $localFullPath = public_path($clean);
        }
        if (!File::exists($localFullPath)) {
            $this->warn("  ! File not found: {$clean}");
            return null;
        }

        $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'bin';
        $newPath = "{$newPrefix}.{$ext}";

        $this->line("  - {$label}: <comment>{$clean}</comment> -> <info>{$newPath}</info>");

        if ($isDryRun) {
            return $newPath; // Return path for counting, skip upload during dry-run
        }

        try {
            Storage::disk('b2')->put($newPath, File::get($localFullPath));
            return $newPath;
        } catch (Throwable $e) {
            $this->error("    Upload error [{$label}]: " . $e->getMessage());
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
