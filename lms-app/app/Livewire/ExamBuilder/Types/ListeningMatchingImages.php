<?php

namespace App\Livewire\ExamBuilder\Types;

use App\Models\HskMockExamQuestionGroup;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ListeningMatchingImages extends BaseQuestionEditor
{
    public $correctAnswers = [];
    public $newPassageImages = [];

    protected function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'correctAnswers.*' => 'nullable',
            'group.passage_image' => 'nullable|string',
            'newPassageImages.*' => 'image|max:2048'
        ]);
    }

    public function mount(HskMockExamQuestionGroup $group)
    {
        parent::mount($group);
        foreach ($this->group->questions as $q) {
            $correctOpt = $q->options->where('is_correct', true)->first();
            if ($correctOpt) {
                $this->correctAnswers[$q->id] = $correctOpt->content; // Store A, B, C...
            }
        }
    }

    public function updatedNewPassageImages()
    {
        $this->validate([
            'newPassageImages.*' => 'image|max:2048',
        ]);

        $safeExamName = $this->group->section->mockExam->folder_name ?? 'mock-exam';
        $folderPath = "hsk_mock_exams/{$safeExamName}/images";
        $currentImages = $this->group->passage_image ? explode(',', $this->group->passage_image) : [];
        
        foreach ($this->newPassageImages as $file) {
            $path = $file->store($folderPath, 'public');
            $currentImages[] = $path;
        }

        $this->group->passage_image = implode(',', $currentImages);
        $this->group->save();
        
        $this->newPassageImages = [];
    }

    public function removePassageImage($index)
    {
        $currentImages = $this->group->passage_image ? explode(',', $this->group->passage_image) : [];
        if (isset($currentImages[$index])) {
            unset($currentImages[$index]);
            $this->group->passage_image = implode(',', array_values($currentImages));
            $this->group->save();
        }
    }

    public function saveGroup()
    {
        Log::info('[LMI::saveGroup] CALLED - correctAnswers count: ' . count($this->correctAnswers) . ' questionTitles count: ' . count($this->questionTitles));
        
        DB::transaction(function () {
            foreach ($this->group->questions as $q) {
                $correctContent = $this->correctAnswers[$q->id] ?? null;
                if ($q->options->isEmpty()) {
                    $q->options()->create([
                        'content' => $correctContent,
                        'is_correct' => true,
                        'order_index' => 1
                    ]);
                } else {
                    $opt = $q->options->first();
                    $opt->content = $correctContent;
                    $opt->is_correct = true;
                    $opt->save();
                }
            }
            parent::saveGroup();
        });
        
        Log::info('[LMI::saveGroup] done, questionTitles:', $this->questionTitles);
    }

    public function render()
    {
        return view('livewire.exam-builder.types.listening-matching-images');
    }
}
