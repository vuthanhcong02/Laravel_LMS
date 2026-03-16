<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === User::ROLE_ADMIN) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === User::ROLE_TEACHER) {
                return redirect()->route('teacher.dashboard');
            }
            return redirect()->route('home');
        }

        return view('portal.admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER])) {
                $request->session()->regenerate();

                if ($user->role === User::ROLE_ADMIN) {
                    return redirect()->route('admin.dashboard');
                }
                else {
                    return redirect()->route('teacher.dashboard');
                }
            }
            else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Tài khoản không có quyền truy cập trang quản trị.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
