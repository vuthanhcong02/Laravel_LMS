<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected $revenueService;

    public function __construct(RevenueService $revenueService)
    {
        $this->revenueService = $revenueService;
    }

    /**
     * Get summary cards data.
     */
    public function getSummaryStats()
    {
        $revenueStats = $this->revenueService->getSummaryStats();

        return [
            'total_students' => User::where('role', User::ROLE_STUDENT)->count(),
            'total_revenue' => $revenueStats['total_revenue'],
            'revenue_growth' => $revenueStats['growth_percentage'],
            'active_courses' => Course::where('is_published', true)->count(),
            'pending_tickets' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
        ];
    }

    /**
     * Get a unified feed of recent activity.
     */
    public function getRecentActivity($limit = 5)
    {
        $activities = collect();

        // New Students
        User::where('role', User::ROLE_STUDENT)
            ->latest()
            ->limit($limit)
            ->get()
            ->each(function ($user) use ($activities) {
            $activities->push([
                'type' => 'user_registered',
                'title' => "Học viên {$user->first_name} {$user->last_name} vừa đăng ký",
                'date' => $user->created_at,
                'icon' => 'person_add',
                'color' => 'blue'
            ]);
        });

        // Successful Payments
        Payment::where('status', 'completed')
            ->with('user')
            ->latest()
            ->limit($limit)
            ->get()
            ->each(function ($payment) use ($activities) {
            $userName = $payment->user ? "{$payment->user->first_name} {$payment->user->last_name}" : "Khách";
            $activities->push([
                'type' => 'payment_success',
                'title' => "Thanh toán thành công: ₫" . number_format($payment->amount) . " từ {$userName}",
                'date' => $payment->created_at,
                'icon' => 'payments',
                'color' => 'emerald'
            ]);
        });

        // New Support Tickets
        SupportTicket::with('user')
            ->latest()
            ->limit($limit)
            ->get()
            ->each(function ($ticket) use ($activities) {
            $activities->push([
                'type' => 'support_request',
                'title' => "Yêu cầu hỗ trợ mới từ {$ticket->user->first_name}",
                'date' => $ticket->created_at,
                'icon' => 'emergency',
                'color' => 'orange'
            ]);
        });

        return $activities->sortByDesc('date')->take($limit);
    }

    /**
     * Get featured statistics like top course.
     */
    public function getFeaturedStats()
    {
        $topCourse = Enrollment::select('course_id', DB::raw('count(*) as count'))
            ->with('course')
            ->groupBy('course_id')
            ->orderBy('count', 'desc')
            ->first();

        return [
            'top_course' => $topCourse ? $topCourse->course->title : 'Chưa có dữ liệu',
            'top_course_students' => $topCourse ? $topCourse->count : 0,
            'satisfaction_rate' => 4.8 // Placeholder, would fetch from reviews if implemented
        ];
    }
}
