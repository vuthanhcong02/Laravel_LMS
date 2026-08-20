<?php

namespace App\Livewire\ExamBuilder;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\HskMockExam;
use App\Models\HskMockExamQuestionGroup;
use Illuminate\Support\Str;

class MainManager extends Component
{
    use WithFileUploads;

    public HskMockExam $exam;

    // Store audio files uploaded for each section
    public $sectionAudios = [];

    // Expanded sections, to let UI know which block is expanded
    public array $expandedSections = ['listening', 'reading', 'writing'];

    // Track which section is opening the add Part dropdown
    public $addingPartToSection = null;

    // Track which Part is being opened for detail editing
    public $editingGroupId = null;

    public function mount(HskMockExam $exam)
    {
        $this->exam = $exam;
    }

    public function toggleSection($sectionId)
    {
        if (in_array($sectionId, $this->expandedSections)) {
            $this->expandedSections = array_diff($this->expandedSections, [$sectionId]);
        } else {
            $this->expandedSections[] = $sectionId;
        }
    }

    public function showAddPartDropdown($sectionId)
    {
        $this->addingPartToSection = $this->addingPartToSection == $sectionId ? null : $sectionId;
    }

    public function toggleEditPart($groupId)
    {
        $this->editingGroupId = $this->editingGroupId == $groupId ? null : $groupId;
    }

    public function updateGroupTitle($groupId, $title)
    {
        $group = HskMockExamQuestionGroup::find($groupId);
        if ($group) {
            $group->update(['title' => $title]);
        }
    }

    public function reorderParts($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            $group = HskMockExamQuestionGroup::find($id);
            if ($group) {
                $group->update(['order_index' => $index + 1]);
            }
        }
    }

    public function updated($property, $value)
    {
        if (str_starts_with($property, 'sectionAudios.')) {
            $sectionId = str_replace('sectionAudios.', '', $property);
            $file = $value;

            // 1. Get section info
            $section = $this->exam->sections()->firstOrCreate(
                ['skill_type' => $sectionId],
                ['order_index' => $this->exam->sections()->count() + 1, 'name' => config('hsk_builder.sections')[$sectionId]['name'] ?? 'Section']
            );

            // 2. Create safe storage path: hsk_mock_exams/{exam_name}/audio
            $safeExamName = $this->exam->folder_name;
            $folderPath = "hsk_mock_exams/{$safeExamName}/audio";
            
            // 3. Save file to 'public' disk
            $path = $file->store($folderPath, 'public');

            // 4. Save path to database
            $section->update([
                'audio_file' => $path
            ]);
            
            // Reset variable after upload
            unset($this->sectionAudios[$sectionId]);
            
            // Thông báo
            $this->dispatch('notify', msg: 'Đã tải lên audio phần thi thành công!', type: 'success');
        }
    }

    public function addPart($sectionId, $questionTypeId)
    {
        $questionTypes = collect(config('hsk_builder.question_types'));
        $qType = $questionTypes->firstWhere('id', $questionTypeId);
        
        if (!$qType) return;

        // Lấy hoặc tạo Section cho đề thi (Nghe/Đọc/Viết)
        $section = $this->exam->sections()->firstOrCreate(
            ['skill_type' => $sectionId],
            ['order_index' => $this->exam->sections()->count() + 1, 'name' => config('hsk_builder.sections')[$sectionId]['name'] ?? 'Section']
        );

        // Lấy thứ tự lớn nhất hiện tại
        $maxOrder = $section->questionGroups()->max('order_index') ?? 0;

        // Tạo Question Group mới
        $section->questionGroups()->create([
            'group_type' => $questionTypeId, 
            'title' => 'Part ' . ($maxOrder + 1),
            'order_index' => $maxOrder + 1,
            'passage_text' => null,
            'passage_audio' => null,
            'passage_image' => null,
        ]);

        $this->addingPartToSection = null; // Đóng dropdown
    }

    public function deletePart($groupId)
    {
        $group = \App\Models\HskMockExamQuestionGroup::find($groupId);
        if ($group) {
            $group->delete();
        }
    }

    public function save()
    {
        $this->dispatch('notify', msg: 'Lưu cài đặt chung thành công!', type: 'success');
    }

    public function render()
    {
        // Load lại dữ liệu mỗi lần render
        $this->exam->load(['sections' => function($q) {
            $q->orderBy('order_index');
        }, 'sections.questionGroups' => function($q) {
            $q->orderBy('order_index');
        }]);

        $sectionsConfig = config('hsk_builder.sections');
        $questionTypesConfig = config('hsk_builder.question_types');
        return view('livewire.exam-builder.main-manager', compact('sectionsConfig', 'questionTypesConfig'));
    }
}
