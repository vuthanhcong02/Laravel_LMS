<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(PageController::class)->group(function () {
    Route::get('/', 'getViewHome')->name('home');
    Route::get('/about', 'getViewAbout')->name('about');
    Route::get('/contact', 'getViewContact')->name('contact');
});

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard/student', [DashboardController::class, 'getStudentDashboard'])->name('student.dashboard');
    Route::get('/dashboard/teacher', [DashboardController::class, 'getTeacherDashboard'])->name('teacher.dashboard');
    Route::get('/dashboard/admin', [DashboardController::class, 'getAdminDashboard'])->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';