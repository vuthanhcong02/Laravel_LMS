<?php

namespace App\Livewire\ExamBuilder\Types;

use App\Models\HskMockExamQuestionGroup;
use Illuminate\Support\Facades\DB;

class ListeningImageChoice extends BaseQuestionEditor
{
    public $correctAnswers = [];

    protected function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'correctAnswers.*' => 'nullable'
        ]);
    }

    public function mount(HskMockExamQuestionGroup $group)
    {
        parent::mount($group);
        foreach ($this->group->questions as $q) {
            $correctOpt = $q->options->where('is_correct', true)->first();
            if ($correctOpt) {
                $this->correctAnswers[$q->id] = $correctOpt->id;
            }
        }
    }

    public function saveGroup()
    {
        DB::transaction(function () {
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
        $maxOrder = $this->group->questions()->max('order_index') ?? 0;
        
        // Create new question
        $q = $this->group->questions()->create([
            'hsk_mock_exam_section_id' => $this->group->hsk_mock_exam_section_id,
            'question_type' => 'single_choice', 
            'order_index' => $maxOrder + 1,
            'points' => 1
        ]);

        // Pre-create 3 default options (A, B, C) for this type
        $q->options()->createMany([
            ['content' => 'A', 'order_index' => 1, 'is_correct' => false],
            ['content' => 'B', 'order_index' => 2, 'is_correct' => false],
            ['content' => 'C', 'order_index' => 3, 'is_correct' => false],
        ]);

        $this->loadGroupData();
    }

    public function render()
    {
        return view('livewire.exam-builder.types.listening-image-choice');
    }
}
