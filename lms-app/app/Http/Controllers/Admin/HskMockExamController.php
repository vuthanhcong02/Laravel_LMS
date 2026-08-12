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
     * Handle creating / importing exam from JSON or CSV
     */
    public function store(StoreHskMockExamRequest $request)
    {
        try {
            $dataFile = $request->file('data_file');
            $extension = $dataFile->getClientOriginalExtension();

            if (in_array($extension, ['csv'])) {
                $data = $this->hskMockExamService->parseCsvToData($dataFile->getRealPath());
            } else {
                $jsonContent = File::get($dataFile->getRealPath());
                $data = json_decode($jsonContent, true);
            }

            $zipRealPath = null;

            // Handle multiple media files by zipping them on the fly
            if ($request->hasFile('media_files')) {
                $zip = new \ZipArchive();
                $zipFileName = tempnam(sys_get_temp_dir(), 'media_') . '.zip';
                if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
                    foreach ($request->file('media_files') as $file) {
                        $originalName = $file->getClientOriginalName();
                        // Put audio files in audio/, images in images/ if needed by importExam
                        // But importExam extracts everything to the same directory and checks `glob`
                        // Actually, importExam just extracts to `$storagePath`. It looks for `audio/*.mp3`.
                        $ext = strtolower($file->getClientOriginalExtension());
                        $folder = in_array($ext, ['mp3', 'wav']) ? 'audio/' : 'images/';
                        $zip->addFile($file->getRealPath(), $folder . $originalName);
                    }
                    $zip->close();
                    $zipRealPath = $zipFileName;
                }
            }

            $result = $this->hskMockExamService->importExam($data, $zipRealPath);

            // Clean up temp zip
            if ($zipRealPath && file_exists($zipRealPath)) {
                @unlink($zipRealPath);
            }

            return redirect()->route('admin.hsk-mock-exams.index')->with(
                'success',
                "Imported exam {$result['exam_id']} successfully with {$result['total_questions']} questions!"
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing exam: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV Template for import
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="hsk_exam_template.csv"',
        ];

        $columns = [
            'level',
            'exam_id',
            'section',
            'part',
            'passage_text',
            'passage_image',
            'question_type',
            'question_text',
            'options',
            'correct_answer',
            'question_image',
            'question_audio'
        ];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            // Add a sample row
            fputcsv($file, [
                '1', // level
                'H10901', // exam_id
                'listening', // section
                '1', // part
                '', // passage_text
                '', // passage_image
                'listening_true_false', // question_type
                'Đây là câu hỏi mẫu', // question_text
                'A: Đúng|B: Sai', // options
                'A', // correct_answer
                'sample_image.png', // question_image
                'sample_audio.mp3', // question_audio
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Handle creating empty exam
     */
    public function storeEmpty(\App\Http\Requests\Admin\StoreEmptyHskMockExamRequest $request)
    {
        try {
            $hskLevel = HskLevel::where('level_code', $request->level_code)->firstOrFail();
            $exam = $this->hskMockExamService->createEmptyExam($request->validated(), $hskLevel);

            return redirect()->route('admin.hsk-mock-exams.edit', $exam->id)->with('success', 'Đã tạo đề thi trống thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi tạo đề thi: ' . $e->getMessage());
        }
    }

    /**
     * Toggle exam publish status
     */
    public function togglePublish(HskMockExam $hskMockExam)
    {
        $hskMockExam->update([
            'is_published' => !$hskMockExam->is_published
        ]);

        $statusStr = $hskMockExam->is_published ? 'xuất bản' : 'chuyển về bản nháp';
        return back()->with('success', "Đã {$statusStr} đề thi thành công!");
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
