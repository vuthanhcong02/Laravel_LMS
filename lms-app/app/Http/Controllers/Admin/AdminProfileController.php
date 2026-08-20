<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\AdminProfileUpdateRequest;
use App\Http\Requests\Admin\Profile\AdminProfilePasswordRequest;
use App\Services\Admin\UserService;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }

    public function edit()
    {
        $user = Auth::user();
        return view('portal.admin.profile.index', compact('user'));
    }

    public function update(AdminProfileUpdateRequest $request)
    {
        $this->userService->updateProfile(Auth::id(), $request->validated(), $request->file('avatar'));

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(AdminProfilePasswordRequest $request)
    {
        $this->userService->updatePassword(Auth::id(), $request->validated('password'));

        return redirect()->route('admin.profile.edit')->with('success', 'Password changed successfully.');
    }
}
