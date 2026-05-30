<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseService
{
    /**
     * Get paginated courses with optional filters + stats.
     */
    public function getByCondition(array $filters)
    {
        return Course::with(['teacher', 'category'])
            ->withCount('enrollments')
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($q) use ($filters) {
                $q->where('is_published', (bool) $filters['status']);
            })
            ->when($filters['category_id'] ?? null, function ($q, $catId) {
                $q->where('category_id', $catId);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * Get summary stats for the index page.
     */
    public function getStats(): array
    {
        return [
            'total'     => Course::count(),
            'published' => Course::where('is_published', true)->count(),
            'draft'     => Course::where('is_published', false)->count(),
        ];
    }

    /**
     * Store a new course.
     */
    public function store(array $data, $thumbnailFile = null): Course
    {
        $data['slug'] = $this->generateUniqueSlug($data['title']);

        if ($thumbnailFile) {
            $data['thumbnail'] = $thumbnailFile->store('courses/thumbnails', 'public');
        }

        $course = Course::create($data);

        // Handle schedules
        $this->syncSchedules($course, $data);

        return $course;
    }

    /**
     * Update an existing course.
     */
    public function update(Course $course, array $data, $thumbnailFile = null): Course
    {
        // Re-slug only if title changed
        if ($data['title'] !== $course->title) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $course->id);
        }

        if ($thumbnailFile) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $thumbnailFile->store('courses/thumbnails', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $course->update($data);

        // Handle schedules
        $this->syncSchedules($course, $data);

        return $course;
    }

    /**
     * Sync course schedules
     */
    protected function syncSchedules(Course $course, array $data): void
    {
        $course->schedules()->delete();

        if (!empty($data['start_time']) && !empty($data['end_time']) && !empty($data['days_of_week'])) {
            foreach ($data['days_of_week'] as $day) {
                $course->schedules()->create([
                    'day_of_week' => $day,
                    'start_time' => $data['start_time'] . ':00',
                    'end_time' => $data['end_time'] . ':00',
                ]);
            }
        }
    }

    /**
     * Delete a course and its thumbnail.
     */
    public function destroy(Course $course): void
    {
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();
    }

    /**
     * Get all categories for dropdowns.
     */
    public function getCategories()
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Get all teacher users for dropdowns.
     */
    public function getTeachers()
    {
        return User::where('role', User::ROLE_TEACHER)->orderBy('first_name')->get();
    }

    /**
     * Generate a unique slug, optionally excluding a given course ID.
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

        while (
            Course::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
