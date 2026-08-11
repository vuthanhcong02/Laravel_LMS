<?php

namespace App\Livewire\ExamBuilder\Types;

use App\Models\HskMockExamQuestionGroup;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReadingMatchingSentences extends BaseQuestionEditor
{
    public $correctAnswers = [];
    public $newPassageImages = [];

    // For text-based options (A-F) - used in HSK 3+
    public $useTextOptions = false;
    public $textOptions = [];
    public $exampleLetter = '';

    protected function rules()
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'correctAnswers.*' => 'nullable|string',
            'group.passage_image' => 'nullable|string',
            'newPassageImages.*' => 'image|max:2048',
            'useTextOptions' => 'boolean',
            'textOptions.*.letter' => 'nullable|string',
            'textOptions.*.pinyin' => 'nullable|string',
            'textOptions.*.hanzi' => 'nullable|string',
            'textOptions.*.html' => 'nullable|string',
            'exampleLetter' => 'nullable|string',
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

        // Initialize text options if they exist
        if ($this->group->passage_text && str_starts_with(trim($this->group->passage_text), '{')) {
            $parsedEx = json_decode(trim($this->group->passage_text), true);
            if (isset($parsedEx['options'])) {
                $this->useTextOptions = true;
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
        
        // Ensure textOptions has at least one element if useTextOptions is checked
        if (empty($this->textOptions)) {
            $this->textOptions = [
                ['letter' => 'A', 'pinyin' => '', 'hanzi' => '', 'html' => '']
            ];
        }
    }

    public function addTextOption()
    {
        $nextLetter = chr(65 + count($this->textOptions));
        $this->textOptions[] = ['letter' => $nextLetter, 'pinyin' => '', 'hanzi' => '', 'html' => ''];
    }

    public function removeTextOption($index)
    {
        unset($this->textOptions[$index]);
        $this->textOptions = array_values($this->textOptions);
    }

    public function updatedNewPassageImages()
    {
        $this->validate([
            'newPassageImages.*' => 'image|max:2048',
        ]);

        $safeExamName = Str::slug($this->group->section->mockExam->title ?? 'mock-exam');
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
        DB::transaction(function () {
            if ($this->useTextOptions) {
                $data = [
                    'ex_a_letter' => $this->exampleLetter,
                    'a_letter' => $this->exampleLetter,
                    'options' => $this->textOptions
                ];
                $this->group->passage_text = json_encode($data, JSON_UNESCAPED_UNICODE);
            } else {
                $this->group->passage_text = null;
            }

            foreach ($this->group->questions as $q) {
                $correctContent = $this->correctAnswers[$q->id] ?? null;
                if ($q->options->isEmpty()) {
                    if ($correctContent) {
                        $q->options()->create([
                            'content' => $correctContent,
                            'is_correct' => true,
                            'order_index' => 1
                        ]);
                    }
                } else {
                    $opt = $q->options->first();
                    $opt->content = $correctContent;
                    $opt->is_correct = true;
                    $opt->save();
                }
            }
            parent::saveGroup();
        });
    }

    public function render()
    {
        return view('livewire.exam-builder.types.reading-matching-sentences');
    }
}
