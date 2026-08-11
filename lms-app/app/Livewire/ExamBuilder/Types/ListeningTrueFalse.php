<?php

namespace App\Livewire\ExamBuilder\Types;

use App\Models\HskMockExamQuestionGroup;
use Illuminate\Support\Facades\DB;

class ListeningTrueFalse extends BaseQuestionEditor
{
    public $trueFalseAnswers = [];

    protected function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'trueFalseAnswers.*' => 'nullable'
        ]);
    }

    public function mount(HskMockExamQuestionGroup $group)
    {
        parent::mount($group);
        $this->initAnswers();
    }

    protected function initAnswers()
    {
        foreach ($this->group->questions as $q) {
            $correctOpt = $q->options->where('is_correct', true)->first();
            if ($correctOpt && $correctOpt->content === '×') {
                $this->trueFalseAnswers[$q->id] = 0;
            } else {
                $this->trueFalseAnswers[$q->id] = 1; // Default is True
            }
        }
    }

    public function addQuestion()
    {
        $maxOrder = $this->group->questions()->max('order_index') ?? 0;
        
        $question = $this->group->questions()->create([
            'hsk_mock_exam_section_id' => $this->group->hsk_mock_exam_section_id,
            'question_type' => 'true_false', // HSK 1,2 Listening Part 1,2
            'order_index' => $maxOrder + 1,
            'points' => 1
        ]);

        // Auto create True/False options
        $question->options()->createMany([
            ['content' => '√', 'order_index' => 1],
            ['content' => '×', 'order_index' => 2]
        ]);

        $this->loadGroupData();
        $this->initAnswers();
    }

    public function saveGroup()
    {
        DB::transaction(function () {
            foreach ($this->group->questions as $q) {
                $val = $this->trueFalseAnswers[$q->id] ?? 1;
                foreach ($q->options as $opt) {
                    if ($opt->content === '√') $opt->is_correct = ($val == 1);
                    if ($opt->content === '×') $opt->is_correct = ($val == 0);
                    $opt->save();
                }
            }
            parent::saveGroup();
        });
    }

    public function render()
    {
        return view('livewire.exam-builder.types.listening-true-false');
    }
}
