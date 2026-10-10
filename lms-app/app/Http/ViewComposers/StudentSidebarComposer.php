<?php

namespace App\Http\ViewComposers;

use App\Models\Assignment;
use App\Models\Enrollment;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentSidebarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with([
                'pendingAssignmentsCount' => 0,
                'pendingQuizzesCount'     => 0,
            ]);
            return;
        }

        $userId = Auth::id();

        // Get list of enrolled course IDs for the student
        $enrolledCourseIds = Enrollment::where('user_id', $userId)
            ->pluck('course_id');

        if ($enrolledCourseIds->isEmpty()) {
            $view->with([
                'pendingAssignmentsCount' => 0,
                'pendingQuizzesCount'     => 0,
            ]);
            return;
        }

        // 1. Count unsubmitted assignments (published and not submitted by the student)
        $pendingAssignmentsCount = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('status', Assignment::STATUS_PUBLISHED)
            ->whereDoesntHave('submissions', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        // 2. Count incomplete quizzes (student has not completed any attempt)
        $pendingQuizzesCount = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->whereDoesntHave('attempts', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->whereNotNull('completed_at');
            })
            ->count();

        $view->with([
            'pendingAssignmentsCount' => $pendingAssignmentsCount,
            'pendingQuizzesCount'     => $pendingQuizzesCount,
        ]);
    }
}
