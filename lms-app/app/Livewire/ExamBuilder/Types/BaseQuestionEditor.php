<?php

namespace App\Livewire\ExamBuilder\Types;

use Livewire\Component;
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
    public $optionContents = [];
    public $questionIds = []; // Store [index => id] for mapping on save

    // Validation rules
    protected function rules()
    {
        return [
            'group.title' => 'nullable|string',
            'group.passage_text' => 'nullable|string',
            'questionTitles.*' => 'nullable|string',
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

        foreach ($this->group->questions as $idx => $question) {
            $this->questionTitles[$idx] = $question->title ?? '';
            $this->questionIds[$idx] = $question->id;
            
            foreach ($question->options as $option) {
                $this->optionContents[$option->id] = $option->content ?? '';
            }
        }
    }

    public function toggleExample($questionId)
    {
        $q = HskMockExamQuestion::find($questionId);
        if ($q) {
            $q->is_example = !$q->is_example;
            $q->save();
            $this->loadGroupData();
        }
    }

    public function saveGroup()
    {
        $this->validate();
        
        DB::transaction(function () {
            $this->group->save();
            
            // Debug: log to see what questionTitles was received
            Log::info('[saveGroup] questionTitles received:', $this->questionTitles);
            Log::info('[saveGroup] questionIds:', $this->questionIds);
            
            // Update questions
            foreach ($this->questionTitles as $idx => $title) {
                $qId = $this->questionIds[$idx] ?? null;
                if (!$qId) continue;
                $question = $this->group->questions->firstWhere('id', $qId);
                if ($question) {
                    $question->title = $title;
                    $result = $question->save();
                    Log::info("[saveGroup] Saved q{$qId} title='" . $title . "' result=" . ($result ? 'OK' : 'FAIL'));
                }
            }
            
            // Update options without N+1 select queries
            $allOptions = $this->group->questions->flatMap->options;
            foreach ($this->optionContents as $optId => $content) {
                $opt = $allOptions->firstWhere('id', $optId);
                if ($opt) {
                    $opt->content = $content;
                    $opt->save();
                }
            }
        });

        $this->dispatch('notify', msg: 'Group saved successfully!', type: 'success');
        $this->loadGroupData();
    }

    public function addQuestion()
    {
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
        $q = HskMockExamQuestion::find($questionId);
        if ($q) {
            $q->delete();
        }
        $this->loadGroupData();
    }

    public function updatedQuestionImages($file, $questionId)
    {
        $q = HskMockExamQuestion::find($questionId);
        if (!$q || !$file) return;

        $safeExamName = Str::slug($this->group->section->mockExam->title ?? 'mock-exam');
        $folderPath = "hsk_mock_exams/{$safeExamName}/images";
        $path = $file->store($folderPath, 'public');

        $q->update(['image' => $path]);
        
        unset($this->questionImages[$questionId]);
        $this->loadGroupData();
    }

    public function updatedOptionImages($file, $optionId)
    {
        $opt = HskMockExamOption::find($optionId);
        if (!$opt || !$file) return;

        $safeExamName = Str::slug($this->group->section->mockExam->title ?? 'mock-exam');
        $folderPath = "hsk_mock_exams/{$safeExamName}/images";
        $path = $file->store($folderPath, 'public');

        $opt->update(['image' => $path]);
        
        unset($this->optionImages[$optionId]);
        $this->loadGroupData();
    }
}
