<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HskLevel;
use App\Models\HskMockExam;
use App\Services\Admin\HskMockExamService;
use App\Http\Requests\Admin\StoreHskMockExamRequest;
use App\Http\Requests\Admin\UpdateHskMockExamEditorRequest;
use App\Http\Requests\Admin\UploadHskMockExamImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HskMockExamController extends Controller
{
    protected HskMockExamService $hskMockExamService;

    public function __construct(HskMockExamService $hskMockExamService)
    {
        $this->hskMockExamService = $hskMockExamService;
    }

    /**
     * List of HSK mock exams
     */
    public function index(Request $request)
    {
        $query = HskMockExam::with('hskLevel')->withCount('sections');

        if ($request->filled('level')) {
            $query->whereHas('hskLevel', function ($q) use ($request) {
                $q->where('level_code', $request->level);
            });
        }

        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        $exams = $query->orderBy('id', 'desc')->paginate(15);
        $levels = HskLevel::all();

        return view('portal.admin.hsk-mock-exams.index', compact('exams', 'levels'));
    }

    /**
     * Form to create new or import JSON
     */
    public function create()
    {
        $levels = HskLevel::all();
        return view('portal.admin.hsk-mock-exams.create', compact('levels'));
    }

    /**
     * Handle creating / importing JSON exam
     */
    public function store(StoreHskMockExamRequest $request)
    {
        try {
            $jsonContent = File::get($request->file('json_file')->getRealPath());
            $data = json_decode($jsonContent, true);
            
            $zipRealPath = $request->hasFile('zip_file') ? $request->file('zip_file')->getRealPath() : null;

            $result = $this->hskMockExamService->importExam($data, $zipRealPath);

            return redirect()->route('admin.hsk-mock-exams.index')->with(
                'success', 
                "Imported exam {$result['exam_id']} successfully with {$result['total_questions']} questions!"
            );

        } catch (\Exception $e) {
            return back()->with('error', 'Error importing exam: ' . $e->getMessage());
        }
    }

    /**
     * Alpine.js Editor interface
     */
    public function edit(HskMockExam $hskMockExam)
    {
        return view('portal.admin.hsk-mock-exams.edit', compact('hskMockExam'));
    }

    /**
     * Get the full JSON data tree of the Exam to render in Alpine.js Editor
     */
    public function getEditorData(HskMockExam $hskMockExam)
    {
        $hskMockExam->load([
            'sections' => function ($q) {
                $q->orderBy('order_index');
            },
            'sections.questionGroups' => function ($q) {
                $q->orderBy('order_index');
            },
            'sections.questionGroups.questions' => function ($q) {
                $q->orderBy('order_index');
            },
            'sections.questionGroups.questions.options' => function ($q) {
                $q->orderBy('order_index');
            }
        ]);

        return response()->json([
            'status' => 'success',
            'exam' => $hskMockExam
        ]);
    }

    /**
     * Save all data edited from Alpine.js Editor
     */
    public function saveEditorData(UpdateHskMockExamEditorRequest $request, HskMockExam $hskMockExam)
    {
        $data = $request->validated();

        try {
            $this->hskMockExamService->updateExamData($hskMockExam, $data);

            return response()->json([
                'status' => 'success',
                'message' => 'Exam updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete exam
     */
    public function destroy(HskMockExam $hskMockExam)
    {
        $hskMockExam->delete();
        return redirect()->route('admin.hsk-mock-exams.index')->with('success', 'Exam deleted successfully!');
    }

    /**
     * Upload an image from local computer
     */
    public function uploadImage(UploadHskMockExamImageRequest $request)
    {
        if ($request->hasFile('image')) {
            $path = $this->hskMockExamService->uploadImage($request->file('image'));
            
            return response()->json([
                'status' => 'success',
                'path' => $path,
                'url' => Storage::url($path),
                'message' => 'Image uploaded successfully!'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Valid image file not found.'
        ], 400);
    }
}
