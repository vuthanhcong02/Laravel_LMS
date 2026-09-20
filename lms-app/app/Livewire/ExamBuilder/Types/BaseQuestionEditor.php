<?php

namespace App\Livewire\ExamBuilder\Types;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\HskMockExamQuestionGroup;
use App\Models\HskMockExamQuestion;
use App\Models\HskMockExamOption;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

abstract class BaseQuestionEditor extends Component
{
    use WithFileUploads;

    public HskMockExamQuestionGroup $group;

    public $questionImages = [];
    public $optionImages = [];
    public $questionTitles = [];
    public $questionExplanations = [];
    public $optionContents = [];
    public $questionIds = []; // Store [index => id] for mapping on save

    // Validation rules
    protected function rules()
    {
        return [
            'group.title' => 'nullable|string',
            'group.passage_text' => 'nullable|string',
            'questionTitles.*' => 'nullable|string',
            'questionExplanations.*' => 'nullable',
            'questionExplanations.*.vi' => 'nullable|string',
            'questionExplanations.*.en' => 'nullable|string',
            'questionExplanations.*.zh' => 'nullable|string',
            'optionContents.*' => 'nullable|string',
            'group.questions.*.is_example' => 'boolean',
            'group.questions.*.points' => 'nullable|numeric',
            'group.questions.*.options.*.is_correct' => 'boolean',
        ];
    }

    public function mount(HskMockExamQuestionGroup $group)
    {
        $this->group = $group;
        $this->loadGroupData();
    }

    protected function loadGroupData()
    {
        // Load relationships needed in child classes
        $this->group->load(['questions' => function($q) {
            $q->orderBy('order_index');
        }, 'questions.options' => function($q) {
            $q->orderBy('order_index');
        }]);

        $this->questionTitles = [];
        $this->questionExplanations = [];
        $this->questionIds = [];
        $this->optionContents = [];

        foreach ($this->group->questions as $idx => $question) {
            $this->questionTitles[$idx] = $question->title ?? '';
            $this->questionExplanations[$idx] = $question->explanation_translations;
            $this->questionIds[$idx] = $question->id;
            
            foreach ($question->options as $option) {
                $this->optionContents[$option->id] = $option->content ?? '';
            }
        }
    }

    public function toggleExample($questionId)
    {
        $this->saveGroupData();
        $q = $this->group->questions()->where('id', $questionId)->first();
        if ($q) {
            $q->is_example = !$q->is_example;
            $q->save();
            $this->loadGroupData();
        }
    }

    protected function saveGroupData()
    {
        DB::transaction(function () {
            $this->group->save();
            
            // Update questions
            foreach ($this->questionTitles as $idx => $title) {
                $qId = $this->questionIds[$idx] ?? null;
                if (!$qId) continue;
                $question = $this->group->questions->firstWhere('id', $qId);
                if ($question) {
                    $question->title = $title;

                    $exp = $this->questionExplanations[$idx] ?? null;
                    if (is_array($exp)) {
                        $hasAny = !empty(trim($exp['vi'] ?? '')) || !empty(trim($exp['en'] ?? '')) || !empty(trim($exp['zh'] ?? ''));
                        $question->explanation = $hasAny ? json_encode($exp, JSON_UNESCAPED_UNICODE) : null;
                    } else {
                        $question->explanation = !empty(trim($exp ?? '')) ? (string) $exp : null;
                    }

                    $question->save();
                }
            }
            
            // Update options
            $allOptions = $this->group->questions->flatMap->options;
            foreach ($this->optionContents as $optId => $content) {
                $opt = $allOptions->firstWhere('id', $optId);
                if ($opt) {
                    $opt->content = $content;
                    $opt->save();
                }
            }
        });
    }

    #[On('save-all-parts')]
    public function handleGlobalSave()
    {
        $this->saveGroup();
    }

    public function saveGroup()
    {
        $this->validate();
        $this->saveGroupData();
        
        $this->dispatch('notify', msg: __('Lưu thành công!'), type: 'success');
        $this->loadGroupData();
    }

    public function addQuestion()
    {
        $this->saveGroupData();
        $maxOrder = $this->group->questions()->max('order_index') ?? 0;
        
        $this->group->questions()->create([
            'hsk_mock_exam_section_id' => $this->group->hsk_mock_exam_section_id,
            'question_type' => 'single_choice', // Default, can be overridden
            'order_index' => $maxOrder + 1,
            'points' => 1
        ]);

        $this->loadGroupData();
    }

    public function deleteQuestion($questionId)
    {
        $this->saveGroupData();
        $q = $this->group->questions()->where('id', $questionId)->first();
        if ($q) {
            $q->delete();
        }
        $this->loadGroupData();
    }

    public function updatedQuestionImages($file, $questionId)
    {
        $q = $this->group->questions()->where('id', $questionId)->first();
        if (!$q || !$file) return;

        $safeExamName = $this->group->section->mockExam->folder_name ?? 'mock-exam';
        $folderPath = "hsk_mock_exams/{$safeExamName}/images";
        $path = $file->store($folderPath, 'public');

        $q->update(['image' => $path]);
        
        unset($this->questionImages[$questionId]);
        $this->loadGroupData();
    }

    public function updatedOptionImages($file, $optionId)
    {
        $opt = HskMockExamOption::whereHas('question', function ($q) {
            $q->where('hsk_mock_exam_question_group_id', $this->group->id);
        })->where('id', $optionId)->first();
        if (!$opt || !$file) return;

        $safeExamName = $this->group->section->mockExam->folder_name ?? 'mock-exam';
        $folderPath = "hsk_mock_exams/{$safeExamName}/images";
        $path = $file->store($folderPath, 'public');

        $opt->update(['image' => $path]);
        
        unset($this->optionImages[$optionId]);
        $this->loadGroupData();
    }
}
