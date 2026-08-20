<?php

namespace App\Livewire\ExamBuilder\Types;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ListeningDialogueChoice extends BaseQuestionEditor
{
    // Store correct answers: [questionId => optionId]
    public $correctAnswers = [];

    // Upload audio for the entire group
    public $groupAudio = null;

    protected function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'correctAnswers.*' => 'nullable',
            'groupAudio' => 'nullable',
        ]);
    }

    public function mount(\App\Models\HskMockExamQuestionGroup $group)
    {
        parent::mount($group);
        foreach ($this->group->questions as $q) {
            $correctOpt = $q->options->where('is_correct', true)->first();
            if ($correctOpt) {
                $this->correctAnswers[$q->id] = $correctOpt->id;
            }
        }
    }

    public function updatedGroupAudio()
    {
        $this->validate(['groupAudio' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:51200']);
        if (!$this->groupAudio) return;

        $safeExamName = $this->group->section->mockExam->folder_name ?? 'mock-exam';
        $folderPath = "hsk_mock_exams/{$safeExamName}/audio";
        $path = $this->groupAudio->store($folderPath, 'public');

        $this->group->passage_audio = $path;
        $this->group->save();
        $this->groupAudio = null;
        $this->dispatch('notify', msg: 'Đã upload audio thành công!', type: 'success');
    }

    public function saveGroup()
    {
        DB::transaction(function () {
            // Save correct answers
            foreach ($this->group->questions as $q) {
                $correctId = $this->correctAnswers[$q->id] ?? null;
                foreach ($q->options as $opt) {
                    $opt->is_correct = ($opt->id == $correctId);
                    $opt->save();
                }
            }
            parent::saveGroup();
        });
    }

    public function addQuestion()
    {
        $this->saveGroupData();
        $maxOrder = $this->group->questions()->max('order_index') ?? 0;

        $q = $this->group->questions()->create([
            'hsk_mock_exam_section_id' => $this->group->hsk_mock_exam_section_id,
            'question_type' => 'single_choice',
            'order_index' => $maxOrder + 1,
            'points' => 1
        ]);

        // Pre-create 3 text options A, B, C
        $q->options()->createMany([
            ['content' => '', 'order_index' => 1, 'is_correct' => false],
            ['content' => '', 'order_index' => 2, 'is_correct' => false],
            ['content' => '', 'order_index' => 3, 'is_correct' => false],
        ]);

        $this->loadGroupData();
    }

    public function render()
    {
        return view('livewire.exam-builder.types.listening-dialogue-choice');
    }
}
