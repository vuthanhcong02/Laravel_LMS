<?php

namespace App\Livewire\ExamBuilder\Types;

use App\Models\HskMockExamQuestionGroup;
use App\Services\Media\MediaStorageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

        $examId = $this->group->section->hsk_mock_exam_id ?? 0;
        $mediaStorage = app(MediaStorageService::class);
        $currentImages = $this->group->passage_image ? explode(',', $this->group->passage_image) : [];
        
        foreach ($this->newPassageImages as $file) {
            $path = $mediaStorage->uploadHskExamMedia($examId, $file, 'images');
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
        DB::transaction(function () {
            foreach ($this->group->questions as $q) {
                $correctContent = $this->correctAnswers[$q->id] ?? null;
                // Delete existing options to clean up any A-F imports
                $q->options()->delete();
                // Create single correct option for matching group
                $q->options()->create([
                    'content' => $correctContent,
                    'is_correct' => true,
                    'order_index' => 1
                ]);
            }
            parent::saveGroup();
        });
    }

    public function render()
    {
        return view('livewire.exam-builder.types.listening-matching-images');
    }
}
