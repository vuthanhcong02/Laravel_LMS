<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\AdminProfileUpdateRequest;
use App\Http\Requests\Admin\Profile\AdminProfilePasswordRequest;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Auth;

class TeacherProfileController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function edit()
    {
        $user = Auth::user();
        return view('portal.teacher.profile.index', compact('user'));
    }

    public function update(AdminProfileUpdateRequest $request)
    {
        $this->userService->updateProfile(Auth::id(), $request->validated(), $request->file('avatar'));

        return redirect()->route('teacher.profile.edit')->with('success', 'Hồ sơ đã được cập nhật thành công.');
    }

    public function updatePassword(AdminProfilePasswordRequest $request)
    {
        $this->userService->updatePassword(Auth::id(), $request->validated('password'));

        return redirect()->route('teacher.profile.edit')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
}
