<?php

namespace App\Services\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class UserService
{
    /**
     * Get all user by condition
     * 
     * @param array $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserByCondition($request)
    {
        return User::query()
            ->latest()
            ->when($request['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                }
                );
            })
            ->when($request['role'] ?? null, function ($query, $role) {
            $query->where('role', $role);
        })
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Store a new user
     * 
     * @param array $data
     * @return \App\Models\User
     */
    public function store($data)
    {
        $data['email_verified_at'] = now();

        return User::create($data);
    }

    /**
     * Update user
     * 
     * @param int $id
     * @param array $data
     * @return \App\Models\User
     */
    public function update($id, $data)
    {
        $user = User::findOrFail($id);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        else {
            unset($data['password']);
        }

        $user->update($data);

        return $user;
    }

    /**
     * Delete user
     * 
     * @param int $id
     * @return \App\Models\User
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return $user;
    }

    /**
     * Update the authenticated user's profile info
     *
     * @param int $id
     * @param array $data
     * @param \Illuminate\Http\UploadedFile|null $avatarFile
     * @return \App\Models\User
     */
    public function updateProfile(int $id, array $data, $avatarFile = null): User
    {
        $user = User::findOrFail($id);

        if ($avatarFile) {
            // Delete old local avatar (skip if it's a social URL)
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $avatarFile->store('avatars', 'public');
        } else {
            // Keep existing avatar, don't overwrite with null
            unset($data['avatar']);
        }

        $user->update($data);

        return $user;
    }

    /**
     * Update the authenticated user's password
     *
     * @param int $id
     * @param string $newPassword
     * @return void
     */
    public function updatePassword(int $id, string $newPassword): void
    {
        $user = User::findOrFail($id);
        // $casts handles hashing automatically
        $user->update(['password' => $newPassword]);
    }
}