<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Course\CourseFilterRequest;
use App\Http\Requests\Admin\Course\CourseStoreRequest;
use App\Http\Requests\Admin\Course\CourseUpdateRequest;
use App\Models\Course;
use App\Services\Admin\CourseService;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function __construct(protected CourseService $service) {}

    public function index(CourseFilterRequest $request)
    {
        $courses    = $this->service->getByCondition($request->validated());
        $stats      = $this->service->getStats();
        $categories = $this->service->getCategories();

        return view('portal.admin.courses.index', compact('courses', 'stats', 'categories'));
    }

    public function create()
    {
        $categories = $this->service->getCategories();
        $teachers   = $this->service->getTeachers();

        return view('portal.admin.courses.create', compact('categories', 'teachers'));
    }

    public function store(CourseStoreRequest $request)
    {
        try {
            $this->service->store(
                $request->validated(),
                $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
            );

            return redirect()->route('admin.courses.index')
                ->with('success', 'Khoá học đã được tạo thành công.');
        } catch (\Exception $e) {
            Log::error('Error creating course: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi tạo khoá học.');
        }
    }

    public function edit(Course $course)
    {
        $course->load(['lessons' => fn ($q) => $q->orderBy('order')]);
        $categories = $this->service->getCategories();
        $teachers   = $this->service->getTeachers();

        return view('portal.admin.courses.edit', compact('course', 'categories', 'teachers'));
    }

    public function update(CourseUpdateRequest $request, Course $course)
    {
        try {
            $this->service->update(
                $course,
                $request->validated(),
                $request->hasFile('thumbnail') ? $request->file('thumbnail') : null
            );

            return redirect()->route('admin.courses.edit', $course)
                ->with('success', 'Khoá học đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating course: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi cập nhật khoá học.');
        }
    }

    public function destroy(Course $course)
    {
        try {
            $this->service->destroy($course);

            return redirect()->route('admin.courses.index')
                ->with('success', 'Khoá học đã được xoá.');
        } catch (\Exception $e) {
            Log::error('Error deleting course: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xoá khoá học.');
        }
    }
}
