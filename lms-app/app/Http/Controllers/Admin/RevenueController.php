<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RevenueService;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    protected $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Display the revenue dashboard.
     */
    public function index()
    {
        $stats = $this->revenueService->getSummaryStats();
        $chartData = $this->revenueService->getChartData();
        $topCourses = $this->revenueService->getRevenueByCourse();

        return view('portal.admin.revenue.index', compact('stats', 'chartData', 'topCourses'));
    }

    /**
     * Display the transaction history.
     */
    public function transactions(Request $request)
    {
        $filters = $request->only(['status', 'search']);
        $transactions = $this->revenueService->getTransactionHistory($filters);

        return view('portal.admin.revenue.transactions', compact('transactions'));
    }
}
