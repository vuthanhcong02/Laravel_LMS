<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\Teacher\TeacherDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @param TeacherDashboardService $dashboardService
     */
    public function __construct(protected TeacherDashboardService $dashboardService) {}

    /**
     * Display the teacher dashboard.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $teacherId = $user->id;

        $stats = $this->dashboardService->getSummaryStats($teacherId);
        $schedules = $this->dashboardService->getTodaySchedules($teacherId);
        $notifications = $this->dashboardService->getRecentNotifications($user, 5);

        return view('portal.teacher.dashboard', compact('stats', 'schedules', 'notifications'));
    }
}
