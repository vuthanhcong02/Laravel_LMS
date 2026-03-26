<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'getViewHome')->name('home');
    Route::get('/about', 'getViewAbout')->name('about');
    Route::get('/contact', 'getViewContact')->name('contact');
    Route::get('/roadmap', 'getViewRoadMap')->name('roadmap');
    Route::get('/courses', 'getViewCourses')->name('courses');
    Route::get('/blog', 'getViewBlog')->name('blog');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Shared user profile
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');

    // User Settings
    Route::prefix('portal')->group(function () {
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });
    // Redirect /dashboard based on role, or use individual routes.
    Route::get('/dashboard', function () {
            $user = Auth::user();
            if ($user->role === User::ROLE_ADMIN)
                return redirect()->route('admin.dashboard');
            if ($user->role === User::ROLE_TEACHER)
                return redirect()->route('teacher.dashboard');
            if ($user->role === User::ROLE_STUDENT)
                return redirect()->route('student.dashboard');
            return redirect()->route('home'); // Guest
        }
        )->name('dashboard');

        // Student Dashboard Route
        Route::middleware('role:' . User::ROLE_STUDENT)->group(function () {
            Route::get('/student/dashboard', function () {
                    return view('portal.student.dashboard');
                }
                )->name('student.dashboard');
            }
            );
        });

// Admin & Teacher Portal
Route::prefix('portal')->group(function () {
    Route::get('login', [AdminAuthController::class , 'showLoginForm'])->name('admin.login')->middleware('guest');
    Route::post('login', [AdminAuthController::class , 'login']);
    Route::post('logout', [AdminAuthController::class , 'logout'])->name('admin.logout');

    Route::middleware(['auth', 'role:' . User::ROLE_ADMIN])->group(function () {
            Route::get('admin/dashboard', function () {
                    return view('portal.admin.dashboard');
                }
                )->name('admin.dashboard');

                Route::resource('admin/users', UserController::class)->names('admin.users');

                Route::get('admin/profile', [AdminProfileController::class , 'edit'])->name('admin.profile.edit');
                Route::put('admin/profile', [AdminProfileController::class , 'update'])->name('admin.profile.update');
                Route::put('admin/profile/password', [AdminProfileController::class , 'updatePassword'])->name('admin.profile.updatePassword');

                Route::post('admin/blogs/preview', [BlogController::class, 'preview'])->name('admin.blogs.preview');
                Route::post('admin/blogs/upload', [BlogController::class, 'upload'])->name('admin.blogs.upload');
                Route::resource('admin/blogs', BlogController::class)->names('admin.blogs');

                Route::resource('admin/courses', CourseController::class)->names('admin.courses');
                Route::post('admin/lessons/{lesson}/move-up', [LessonController::class, 'moveUp'])->name('admin.lessons.moveUp');
                Route::post('admin/lessons/{lesson}/move-down', [LessonController::class, 'moveDown'])->name('admin.lessons.moveDown');
                Route::post('admin/courses/{course}/lessons', [LessonController::class, 'store'])->name('admin.lessons.store');
                Route::put('admin/lessons/{lesson}', [LessonController::class, 'update'])->name('admin.lessons.update');
                Route::delete('admin/lessons/{lesson}', [LessonController::class, 'destroy'])->name('admin.lessons.destroy');
            }
            );

            Route::middleware(['auth', 'role:' . User::ROLE_TEACHER])->group(function () {
            Route::get('teacher/dashboard', function () {
                    return view('portal.teacher.dashboard');
                }
                )->name('teacher.dashboard');
            }
            );
        });

require __DIR__ . '/auth.php';
