<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function create($data = [])
    {
        $user = new User;
        $user->name = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->avatar = $data['avatar'] ?? 'default.png';
        $user->role = $data['role'];
        $user->save();

        return $user;
    }

    public function findById($id)
    {
        return User::findorFail($id);
    }

    public function delete($id)
    {
        return User::destroy($id);
    }

    public function getAll()
    {
        return User::orderBy('id', 'desc')->paginate(7);
    }

    public function update($id, $data = [])
    {
        $user = User::findOrFail($id);

        $user->update($data);

        return $user;
    }
}
