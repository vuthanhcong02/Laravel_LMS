<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseSchedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display the calendar view and return events as JSON if requested.
     */
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        // If AJAX request or FullCalendar request (has 'start' param), return JSON
        if ($request->wantsJson() || $request->ajax() || $request->has('start')) {
            $schedules = CourseSchedule::with('course')
                ->whereHas('course', function($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                })->get();

            // Transform for FullCalendar recurring events
            $events = $schedules->map(function($schedule) {
                // Generate a consistent color based on course ID
                $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];
                $colorIndex = $schedule->course_id % count($colors);
                
                $event = [
                    'id' => $schedule->id,
                    'title' => $schedule->course->title,
                    'course_id' => $schedule->course_id,
                    'daysOfWeek' => [$schedule->day_of_week],
                    'startTime' => $schedule->start_time,
                    'endTime' => $schedule->end_time,
                    'backgroundColor' => $colors[$colorIndex],
                    'borderColor' => $colors[$colorIndex],
                    'textColor' => '#ffffff',
                ];
                
                if ($schedule->course->start_date) {
                    $event['startRecur'] = $schedule->course->start_date->format('Y-m-d');
                }
                if ($schedule->course->end_date) {
                    $event['endRecur'] = $schedule->course->end_date->addDay()->format('Y-m-d');
                }

                return $event;
            });

            return response()->json($events);
        }

        // Return view with courses for the "Add Schedule" modal dropdown
        $courses = Course::where('teacher_id', $teacherId)->get();

        return view('portal.teacher.schedules.index', compact('courses'));
    }

}
