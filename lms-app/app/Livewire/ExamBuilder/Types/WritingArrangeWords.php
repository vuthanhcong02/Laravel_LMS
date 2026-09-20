<?php

namespace App\Livewire\ExamBuilder\Types;

use App\Models\HskMockExamQuestionGroup;
use Illuminate\Support\Facades\DB;

class WritingArrangeWords extends BaseQuestionEditor
{
    // Store full correct sentence answers: [questionId => text]
    public $correctAnswers = [];

    protected function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'correctAnswers.*' => 'nullable|string',
        ]);
    }

    public function mount(HskMockExamQuestionGroup $group)
    {
        parent::mount($group);
        $this->initCorrectAnswers();
    }

    protected function initCorrectAnswers()
    {
        $this->correctAnswers = [];
        foreach ($this->group->questions as $q) {
            $correctOpt = $q->options->where('is_correct', true)->first();
            $this->correctAnswers[$q->id] = $correctOpt ? $correctOpt->content : '';
        }
    }

    public function addQuestion()
    {
        $this->saveGroupData();
        $maxOrder = $this->group->questions()->max('order_index') ?? 0;

        $q = $this->group->questions()->create([
            'hsk_mock_exam_section_id' => $this->group->hsk_mock_exam_section_id,
            'question_type' => 'writing_arrange_words',
            'order_index' => $maxOrder + 1,
            'points' => 1
        ]);

        // Create a single correct option record to hold the full sentence
        $q->options()->create([
            'content' => '',
            'is_correct' => true,
            'order_index' => 1
        ]);

        $this->loadGroupData();
        $this->initCorrectAnswers();
    }

    public function saveGroup()
    {
        $this->validate();

        DB::transaction(function () {
            foreach ($this->group->questions as $q) {
                $ansText = trim($this->correctAnswers[$q->id] ?? '');
                $correctOpt = $q->options->where('is_correct', true)->first();
                if ($correctOpt) {
                    $correctOpt->content = $ansText;
                    $correctOpt->save();
                    // Sync into optionContents so parent::saveGroupData does not overwrite with stale data
                    $this->optionContents[$correctOpt->id] = $ansText;
                } elseif ($ansText !== '') {
                    $newOpt = $q->options()->create([
                        'content' => $ansText,
                        'is_correct' => true,
                        'order_index' => 1
                    ]);
                    $this->optionContents[$newOpt->id] = $ansText;
                }
            }
            parent::saveGroupData();
        });

        $this->loadGroupData();
        $this->initCorrectAnswers();

        $this->dispatch('notify', msg: __('Đã lưu phần thi Sắp xếp từ thành công!'), type: 'success');
    }

    public function render()
    {
        return view('livewire.exam-builder.types.writing-arrange-words');
    }
}
