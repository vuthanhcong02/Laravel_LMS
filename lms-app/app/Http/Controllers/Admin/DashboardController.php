<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\RevenueService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $revenueService;

    public function __construct(DashboardService $dashboardService, RevenueService $revenueService)
    {
        $this->dashboardService = $dashboardService;
        $this->revenueService = $revenueService;
    }

    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = $this->dashboardService->getSummaryStats();
        $activities = $this->dashboardService->getRecentActivity();
        $featured = $this->dashboardService->getFeaturedStats();
        $chartData = $this->revenueService->getChartData();

        return view('portal.admin.dashboard', compact('stats', 'activities', 'featured', 'chartData'));
    }
}
