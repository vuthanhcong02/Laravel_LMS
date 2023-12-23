<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\IUserRepository;

class UserRepository implements IUserRepository
{
    public function createOrUpdate($id = null, $data = [])
    {
        $user = $id ? User::find($id) : new User();
        $user->name = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->avatar = $data['avatar'] ?? "default.png";
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
}
