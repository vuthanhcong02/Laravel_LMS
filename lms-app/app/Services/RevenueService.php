<?php

namespace App\Services;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueService
{
    /**
     * Get summary statistics for the revenue dashboard.
     */
    public function getSummaryStats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        
        $thisMonthRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $now])
            ->sum('amount');

        $lastMonthRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');

        // Calculate growth percentage
        $growth = 0;
        if ($lastMonthRevenue > 0) {
            $growth = (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        }

        $totalTransactions = Payment::where('status', 'completed')->count();
        $avgOrderValue = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        return [
            'total_revenue' => $totalRevenue,
            'this_month_revenue' => $thisMonthRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'growth_percentage' => round($growth, 1),
            'avg_order_value' => round($avgOrderValue, 0),
            'total_transactions' => $totalTransactions
        ];
    }

    /**
     * Get chart data for revenue trends.
     */
    public function getChartData($period = 'month')
    {
        $query = Payment::where('status', 'completed')
            ->select(
                DB::raw('SUM(amount) as total'),
                DB::raw('DATE(created_at) as date')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC');

        if ($period === 'month') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        } elseif ($period === 'year') {
            $query->where('created_at', '>=', Carbon::now()->subYear());
        }

        $data = $query->get();

        return [
            'labels' => $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d/m'))->toArray(),
            'values' => $data->pluck('total')->toArray()
        ];
    }

    /**
     * Get revenue by course.
     */
    public function getRevenueByCourse($limit = 5)
    {
        return Payment::where('status', 'completed')
            ->select('course_id', DB::raw('SUM(amount) as total'))
            ->with('course:id,title')
            ->groupBy('course_id')
            ->orderBy('total', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Get paginated transaction history.
     */
    public function getTransactionHistory($filters = [])
    {
        $query = Payment::with(['user', 'course'])
            ->latest();

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('first_name', 'like', "%$search%")
                      ->orWhere('last_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                })->orWhereHas('course', function($cq) use ($search) {
                    $cq->where('title', 'like', "%$search%");
                })->orWhere('transaction_id', 'like', "%$search%");
            });
        }

        return $query->paginate(15);
    }
}
