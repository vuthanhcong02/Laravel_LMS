<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupportController as PortalSupportController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Teacher\ClassController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Teacher\ScheduleController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use App\Http\Controllers\Teacher\TeacherReportController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// ─── Public pages ────────────────────────────────────────────────────────────
Route::controller(PageController::class)->group(function () {
    Route::get('/', 'getViewHome')->name('home');
    Route::get('/about', 'getViewAbout')->name('about');
    Route::get('/contact', 'getViewContact')->name('contact');
    Route::get('/roadmap', 'getViewRoadMap')->name('roadmap');
    Route::get('/courses', 'getViewCourses')->name('courses');
    Route::get('/blog', 'getViewBlog')->name('blog');
});

// ─── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Shared profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/file-viewer', [\App\Http\Controllers\FileController::class, 'show'])->name('file.viewer');

    // Role-based dashboard redirect
    Route::get('/dashboard', function () {
        $role = Auth::user()->role;
        return match ($role) {
            User::ROLE_ADMIN   => redirect()->route('admin.dashboard'),
            User::ROLE_TEACHER => redirect()->route('teacher.dashboard'),
            User::ROLE_STUDENT => redirect()->route('student.dashboard'),
            default            => redirect()->route('home'),
        };
    })->name('dashboard');

    // ─── Portal prefix (all portal pages) ────────────────────────────────────
    Route::prefix('portal')->group(function () {

        // Settings & Support (any authenticated user)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/support', [PortalSupportController::class, 'index'])->name('support.index');
        Route::get('/support/create', [PortalSupportController::class, 'create'])->name('support.create');
        Route::post('/support', [PortalSupportController::class, 'store'])->name('support.store');
        Route::get('/support/{ticket}', [PortalSupportController::class, 'show'])->name('support.show');
        Route::post('/support/{ticket}/reply', [PortalSupportController::class, 'reply'])->name('support.reply');

        // ─── Student routes ───────────────────────────────────────────────────
        Route::middleware('role:' . User::ROLE_STUDENT)->group(function () {
            Route::get('student/dashboard', fn() => view('portal.student.dashboard'))->name('student.dashboard');
            Route::get('student/assignments', [StudentAssignmentController::class, 'index'])->name('student.assignments.index');
            Route::post('student/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('student.assignments.submit');
        });

        // ─── Admin routes ─────────────────────────────────────────────────────
        Route::middleware(['role:' . User::ROLE_ADMIN])->group(function () {
            Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

            Route::resource('admin/users', UserController::class)->names('admin.users');

            Route::get('admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
            Route::put('admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
            Route::put('admin/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.updatePassword');

            Route::post('admin/blogs/preview', [BlogController::class, 'preview'])->name('admin.blogs.preview');
            Route::post('admin/blogs/upload', [BlogController::class, 'upload'])->name('admin.blogs.upload');
            Route::resource('admin/blogs', BlogController::class)->names('admin.blogs');

            Route::resource('admin/courses', CourseController::class)->names('admin.courses');
            Route::post('admin/lessons/{lesson}/move-up', [LessonController::class, 'moveUp'])->name('admin.lessons.moveUp');
            Route::post('admin/lessons/{lesson}/move-down', [LessonController::class, 'moveDown'])->name('admin.lessons.moveDown');
            Route::post('admin/courses/{course}/lessons', [LessonController::class, 'store'])->name('admin.lessons.store');
            Route::put('admin/lessons/{lesson}', [LessonController::class, 'update'])->name('admin.lessons.update');
            Route::delete('admin/lessons/{lesson}', [LessonController::class, 'destroy'])->name('admin.lessons.destroy');

            Route::get('admin/support', [SupportController::class, 'index'])->name('admin.support.index');
            Route::get('admin/support/{ticket}', [SupportController::class, 'show'])->name('admin.support.show');
            Route::post('admin/support/{ticket}/reply', [SupportController::class, 'reply'])->name('admin.support.reply');
            Route::put('admin/support/{ticket}/status', [SupportController::class, 'updateStatus'])->name('admin.support.updateStatus');

            Route::get('admin/revenue', [RevenueController::class, 'index'])->name('admin.revenue.index');
            Route::get('admin/revenue/transactions', [RevenueController::class, 'transactions'])->name('admin.revenue.transactions');

            Route::get('admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
            Route::post('admin/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead');
            Route::post('admin/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.markAllAsRead');
        });

        // ─── Teacher routes ───────────────────────────────────────────────────
        Route::middleware(['role:' . User::ROLE_TEACHER])->group(function () {
            Route::get('teacher/dashboard', [TeacherDashboardController::class, 'index'])->name('teacher.dashboard');

            Route::resource('teacher/classes', ClassController::class)
                ->names('teacher.classes')
                ->only(['index', 'show'])
                ->parameters(['classes' => 'course']);

            Route::resource('teacher/assignments', TeacherAssignmentController::class)
                ->names('teacher.assignments');

            Route::post(
                'teacher/assignments/{assignment}/grade/{submission}',
                [TeacherAssignmentController::class, 'grade']
            )->name('teacher.assignments.grade');

            // Quizzes Management
            Route::get('teacher/quizzes/export-template', [QuizController::class, 'exportTemplate'])
                ->name('teacher.quizzes.export-template');

            Route::resource('teacher/quizzes', QuizController::class)
                ->names('teacher.quizzes')
                ->except(['show']);

            Route::get('teacher/quizzes/{quiz}/questions', [QuizController::class, 'questions'])
                ->name('teacher.quizzes.questions');
            Route::put('teacher/quizzes/{quiz}/questions', [QuizController::class, 'updateQuestions'])
                ->name('teacher.quizzes.questions.update');
            Route::post('teacher/quizzes/{quiz}/import', [QuizController::class, 'import'])
                ->name('teacher.quizzes.import');

            // Reports Management
            Route::resource('teacher/reports', TeacherReportController::class)
                ->names('teacher.reports')
                ->only(['index', 'show']);

            Route::get('teacher/reports/{report}/export-pdf', [TeacherReportController::class, 'exportPdf'])
                ->name('teacher.reports.export-pdf')
                ->middleware('throttle:10,1');

            // Schedules Management
            Route::get('teacher/schedules', [ScheduleController::class, 'index'])->name('teacher.schedules.index');

            Route::get('teacher/profile', [TeacherProfileController::class, 'edit'])->name('teacher.profile.edit');
            Route::put('teacher/profile', [TeacherProfileController::class, 'update'])->name('teacher.profile.update');
            Route::put('teacher/profile/password', [TeacherProfileController::class, 'updatePassword'])->name('teacher.profile.updatePassword');
        });

        // Login / Logout (no auth needed)
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

require __DIR__ . '/auth.php';
