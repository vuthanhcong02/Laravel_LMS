<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentDashboardService;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{

    public function __construct(protected StudentDashboardService $dashboardService) {}

    /**
     * Display the student dashboard with dynamic data.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Fetch dynamic data from the dashboard service
        $stats = $this->dashboardService->getSummaryStats($userId);
        $continuingCourses = $this->dashboardService->getContinuingCourses($userId);
        $schedules = $this->dashboardService->getUpcomingSchedules($userId);
        $todoTasks = $this->dashboardService->getTodoTasks($userId);

        return view('portal.student.dashboard', compact(
            'stats',
            'continuingCourses',
            'schedules',
            'todoTasks'
        ));
    }
}
