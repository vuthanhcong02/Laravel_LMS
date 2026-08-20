<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\Admin\UserService;
use App\Http\Requests\Admin\User\UserFilterRequest;
use App\Http\Requests\Admin\User\UserStoreRequest;
use App\Http\Requests\Admin\User\UserUpdateRequest;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {
    }
    public function index(UserFilterRequest $request)
    {
        $users = $this->userService->getUserByCondition($request->validated());
        $roles = User::getAllRole();

        return view('portal.admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = User::getAllRole();

        return view('portal.admin.users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request)
    {
        $this->userService->store($request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = User::getAllRole();

        return view('portal.admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $this->userService->update($user->id, $request->validated());

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->userService->destroy($user->id);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
