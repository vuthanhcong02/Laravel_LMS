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
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\HskMockExamController as AdminHskMockExamController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PinyinController;
use App\Http\Controllers\PinyinQuizController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\HskMockExamController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\StudentQuizController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignmentController;
use App\Http\Controllers\Teacher\ClassController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Teacher\ScheduleController;
use App\Http\Controllers\Teacher\TeacherProfileController;
use App\Http\Controllers\Teacher\TeacherReportController;
use App\Http\Controllers\Teacher\HskMockExamController as TeacherHskMockExamController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// ─── Public pages ────────────────────────────────────────────────────────────
Route::controller(PageController::class)->group(function () {
    Route::get('/', function () {
        return redirect()->route('home');
    });
    // Old Home
    Route::get('/trang-chu-old-v2', 'getViewHome')->name('home.old-v2');
    Route::get('/lien-he', [PageController::class, 'getViewContact'])->name('contact');
    Route::post('/lien-he', [ContactController::class, 'store'])->middleware('throttle:3,1')->name('contact.store');
    // Old Routes
    Route::get('/khoa-hoc-old-v2', 'getViewCourses')->name('courses.old-v2');
    Route::get('/khoa-hoc-old-v2/{levelSlug}/{lessonSlug}/{tab?}', 'showCourseLesson')->name('courses.old-v2.lesson')->whereIn('tab', ['tu-vung', 'hoi-thoai', 'ngu-phap', 'luyen-tap']);

    // V2 Routes (now main)
    Route::get('/khoa-hoc', 'getViewCoursesV2')->name('courses');
    Route::get('/khoa-hoc/{levelSlug}', 'showCourseLevelV2')->name('courses.level');
    Route::get('/khoa-hoc/{levelSlug}/{lessonSlug}/{tab?}', 'showCourseLessonV2')->name('courses.lesson')->whereIn('tab', ['tu-vung', 'hoi-thoai', 'ngu-phap', 'luyen-tap']);
    Route::get('/goc-chia-se', 'getViewBlog')->name('blog');
    Route::get('/bang-phien-am-pinyin', [PinyinController::class, 'index'])->name('pinyin.index');
    Route::get('/luyen-tap-pinyin', [PinyinQuizController::class, 'index'])->name('pinyin.quiz');
    Route::get('/thi-thu-hsk', [HskMockExamController::class, 'index'])->name('student.hsk-mock-exams.index');
    Route::get('/thi-thu-hsk/{level}', [HskMockExamController::class, 'show'])->name('student.hsk-mock-exams.show');
    // New Home
    Route::get('/trang-chu', 'getDemoHome')->name('home');
    Route::get('/the-ghi-nho', 'getViewFlashcards')->name('flashcards');
    Route::post('/flashcards/remember', 'rememberVocabulary')->name('flashcards.remember');
    Route::post('/flashcards/unremember', 'unrememberVocabulary')->name('flashcards.unremember');
    Route::post('/flashcards/reset', 'resetVocabularyProgress')->name('flashcards.reset');
    Route::view('/demo-courses', 'demo-courses');
    Route::view('/demo-course-detail', 'demo-course-detail');
    Route::view('/demo-exams', 'demo-exams');
    Route::view('/demo-exam-take', 'demo-exam-take');
    Route::view('/demo-flashcards', 'demo-flashcards');
    Route::view('/demo-etymology', 'demo-etymology');
    Route::view('/login', 'auth.login');
    Route::view('/register', 'auth.register');
    Route::view('/forgot-password', 'auth.forgot-password');
    Route::view('/reset-password', 'auth.reset-password');
});

// ─── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // HSK Mock Exams (Take Exam via Session UUID)
    Route::get('/thi-thu-hsk/{level}/bai-thi/{id}', [HskMockExamController::class, 'startExam'])->name('student.hsk-mock-exams.start');
    Route::get('/thi-thu-hsk/lam-bai/{uuid}', [HskMockExamController::class, 'takeExam'])->name('student.hsk-mock-exams.take');
    Route::post('/thi-thu-hsk/lam-bai/{uuid}/submit', [HskMockExamController::class, 'submitExam'])->name('student.hsk-mock-exams.submit');
    Route::get('/thi-thu-hsk/ket-qua/{uuid}', [HskMockExamController::class, 'showResult'])->name('student.hsk-mock-exams.result');

    // Shared profile
    Route::get('/ho-so-ca-nhan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/ho-so-ca-nhan', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/ho-so-ca-nhan', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/xem-tai-lieu', [FileController::class, 'show'])->name('file.viewer');

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
            Route::get('student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

            Route::get('student/courses', [StudentCourseController::class, 'index'])->name('student.courses.index');
            Route::get('student/courses/{course}', [StudentCourseController::class, 'show'])->name('student.courses.show');
            Route::get('student/courses/{course}/learn/{lesson?}', [StudentCourseController::class, 'learn'])->name('student.courses.learn');

            Route::get('student/assignments', [StudentAssignmentController::class, 'index'])->name('student.assignments.index');
            Route::get('student/assignments/{assignment}', [StudentAssignmentController::class, 'show'])->name('student.assignments.show');
            Route::post('student/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('student.assignments.submit');

            Route::get('student/quizzes', [StudentQuizController::class, 'index'])->name('student.quizzes.index');
            Route::get('student/quizzes/{quiz}', [StudentQuizController::class, 'show'])->name('student.quizzes.show');
            Route::post('student/quizzes/{quiz}/attempt', [StudentQuizController::class, 'attempt'])->name('student.quizzes.attempt');
            Route::get('student/quizzes/attempts/{attempt}', [StudentQuizController::class, 'take'])->name('student.quizzes.take');
            Route::post('student/quizzes/attempts/{attempt}/submit', [StudentQuizController::class, 'submit'])->name('student.quizzes.submit');
            Route::get('student/quizzes/attempts/{attempt}/result', [StudentQuizController::class, 'result'])->name('student.quizzes.result');

            Route::get('student/profile', [StudentProfileController::class, 'edit'])->name('student.profile.edit');
            Route::put('student/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');
            Route::put('student/profile/password', [StudentProfileController::class, 'updatePassword'])->name('student.profile.updatePassword');
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

            Route::get('admin/contacts', [AdminContactController::class, 'index'])->name('admin.contacts.index');
            Route::get('admin/contacts/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
            Route::put('admin/contacts/{contact}/status', [AdminContactController::class, 'updateStatus'])->name('admin.contacts.updateStatus');

            Route::get('admin/revenue', [RevenueController::class, 'index'])->name('admin.revenue.index');
            Route::get('admin/revenue/transactions', [RevenueController::class, 'transactions'])->name('admin.revenue.transactions');

            Route::get('admin/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
            Route::post('admin/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.markAsRead');
            Route::post('admin/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('admin.notifications.markAllAsRead');

            // ─── Backup & Monitoring routes ──────────────────────────────────────
            Route::get('admin/backup', [BackupController::class, 'index'])->name('admin.backup.index');
            Route::post('admin/backup/settings', [BackupController::class, 'updateSettings'])->name('admin.backup.settings');
            Route::post('admin/backup/run-now', [BackupController::class, 'runNow'])->name('admin.backup.run-now');
            Route::get('admin/backup/download/{filename}', [BackupController::class, 'download'])->name('admin.backup.download')->where('filename', '.+');
            Route::delete('admin/backup/{filename}', [BackupController::class, 'destroy'])->name('admin.backup.destroy')->where('filename', '.+');
            Route::get('admin/monitoring', [MonitoringController::class, 'index'])->name('admin.monitoring.index');

            // ─── HSK Mock Exams ──────────────────────────────────────────────────
            Route::resource('admin/hsk-mock-exams', AdminHskMockExamController::class)->names('admin.hsk-mock-exams');
            Route::post('admin/hsk-mock-exams/store-empty', [AdminHskMockExamController::class, 'storeEmpty'])->name('admin.hsk-mock-exams.store-empty');
            Route::patch('admin/hsk-mock-exams/{hsk_mock_exam}/toggle-publish', [AdminHskMockExamController::class, 'togglePublish'])->name('admin.hsk-mock-exams.toggle-publish');
            Route::get('admin/hsk-mock-exams/download-template', [AdminHskMockExamController::class, 'downloadTemplate'])->name('admin.hsk-mock-exams.download-template');
            Route::get('admin/hsk-mock-exams/{hsk_mock_exam}/editor-data', [AdminHskMockExamController::class, 'getEditorData'])->name('admin.hsk-mock-exams.editor-data');
            Route::put('admin/hsk-mock-exams/{hsk_mock_exam}/editor-data', [AdminHskMockExamController::class, 'saveEditorData'])->name('admin.hsk-mock-exams.save-editor-data');
            Route::post('admin/hsk-mock-exams/upload-image', [AdminHskMockExamController::class, 'uploadImage'])->name('admin.hsk-mock-exams.upload-image');
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

            // ─── HSK Mock Exams ──────────────────────────────────────────────────
            Route::resource('teacher/hsk-mock-exams', TeacherHskMockExamController::class)->names('teacher.hsk-mock-exams');
            Route::post('teacher/hsk-mock-exams/store-empty', [TeacherHskMockExamController::class, 'storeEmpty'])->name('teacher.hsk-mock-exams.store-empty');
            Route::patch('teacher/hsk-mock-exams/{hsk_mock_exam}/toggle-publish', [TeacherHskMockExamController::class, 'togglePublish'])->name('teacher.hsk-mock-exams.toggle-publish');
            Route::get('teacher/hsk-mock-exams/download-template', [TeacherHskMockExamController::class, 'downloadTemplate'])->name('teacher.hsk-mock-exams.download-template');
            Route::get('teacher/hsk-mock-exams/{hsk_mock_exam}/editor-data', [TeacherHskMockExamController::class, 'getEditorData'])->name('teacher.hsk-mock-exams.editor-data');
            Route::put('teacher/hsk-mock-exams/{hsk_mock_exam}/editor-data', [TeacherHskMockExamController::class, 'saveEditorData'])->name('teacher.hsk-mock-exams.save-editor-data');
            Route::post('teacher/hsk-mock-exams/upload-image', [TeacherHskMockExamController::class, 'uploadImage'])->name('teacher.hsk-mock-exams.upload-image');
        });

        // Login / Logout (no auth needed)
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login')->middleware('guest');
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});

require __DIR__ . '/auth.php';

Route::get('/debug-db', function () {
    $lesson = App\Models\Lesson::find(21);
    $lesson->load('practices.sections.questions');
    $out = "";
    foreach ($lesson->practices as $practice) {
        foreach ($practice->sections as $section) {
            foreach ($section->questions as $q) {
                if (!empty($q->question_segments)) {
                    $out .= "Question ID: {$q->id}\n";
                    $out .= "Type: " . gettype($q->question_segments) . "\n";
                    $out .= "Value: " . json_encode($q->question_segments, JSON_UNESCAPED_UNICODE) . "\n\n";
                }
            }
        }
    }
    return response($out)->header('Content-Type', 'text/plain');
});
