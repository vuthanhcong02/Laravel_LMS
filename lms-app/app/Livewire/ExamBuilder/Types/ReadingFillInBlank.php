<?php

namespace App\Livewire\ExamBuilder\Types;

use App\Models\HskMockExamQuestionGroup;
use Illuminate\Support\Facades\DB;

class ReadingFillInBlank extends BaseQuestionEditor
{
    public $correctAnswers = [];
    public $textOptions = [];
    public $exampleLetter = '';

    public function mount(HskMockExamQuestionGroup $group)
    {
        parent::mount($group);
        $this->initData();
    }

    protected function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'correctAnswers.*' => 'nullable|string',
            'textOptions.*.letter' => 'nullable|string',
            'textOptions.*.html' => 'nullable|string',
            'exampleLetter' => 'nullable|string',
        ]);
    }

    protected function initData()
    {
        // Initialize text options if they exist in passage_text
        if ($this->group->passage_text && str_starts_with(trim($this->group->passage_text), '{')) {
            $parsedEx = json_decode(trim($this->group->passage_text), true);
            if (isset($parsedEx['options'])) {
                $options = $parsedEx['options'];
                foreach ($options as $idx => &$opt) {
                    if (!isset($opt['letter'])) {
                        $opt['letter'] = chr(65 + $idx);
                    }
                }
                $this->textOptions = $options;
                $this->exampleLetter = $parsedEx['ex_a_letter'] ?? $parsedEx['a_letter'] ?? '';
            }
        }
        
        // Populate correctAnswers
        foreach ($this->group->questions as $q) {
            $correctOpt = $q->options->where('is_correct', true)->first();
            if ($correctOpt) {
                $this->correctAnswers[$q->id] = $correctOpt->content;
            }
        }
    }

    public function addTextOption()
    {
        $this->textOptions[] = [
            'letter' => chr(65 + count($this->textOptions)),
            'pinyin' => '',
            'hanzi' => '',
            'html' => ''
        ];
    }

    public function removeTextOption($index)
    {
        unset($this->textOptions[$index]);
        $this->textOptions = array_values($this->textOptions);
        
        // Re-assign letters A, B, C...
        foreach ($this->textOptions as $idx => &$opt) {
            $opt['letter'] = chr(65 + $idx);
        }
    }

    public function addQuestion()
    {
        $maxOrder = $this->group->questions()->max('order_index') ?? 0;
        
        $q = $this->group->questions()->create([
            'hsk_mock_exam_section_id' => $this->group->hsk_mock_exam_section_id,
            'question_type' => 'fill_blank', 
            'order_index' => $maxOrder + 1,
            'points' => 1
        ]);

        $this->loadGroupData();
    }

    public function saveGroup()
    {
        DB::transaction(function () {
            // Save text options into passage_text as JSON
            $data = [
                'ex_a_letter' => $this->exampleLetter,
                'a_letter' => $this->exampleLetter, // keep for backward compatibility if needed
                'options' => $this->textOptions
            ];
            $this->group->passage_text = json_encode($data, JSON_UNESCAPED_UNICODE);

            // Save correct answers
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

            // Standard save flow from BaseQuestionEditor
            parent::saveGroup();
        });
    }

    public function render()
    {
        return view('livewire.exam-builder.types.reading-fill-in-blank');
    }
}
