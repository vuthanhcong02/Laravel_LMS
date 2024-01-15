<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'username' => 'required|min:3',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role' => 'required',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            return response()->json([
                'status' => true,
                'data' => $this->userRepository->create($data),
                'message' => 'user created successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Xử lý lỗi validation
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            // Xử lý các ngoại lệ khác
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $this->userRepository->findById($id);

        if ($user) {
            return response()->json([
                'status' => true,
                'data' => $user,
                'message' => 'User found',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'username' => 'min:3',
                'password' => 'nullable|min:6',
                'role' => 'nullable|in:student,teacher,admin',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            // $data = $request->all();
            // dd($data);
            return response()->json([
                'status' => true,
                'data' => $this->userRepository->update($id, $data),
                'message' => 'user updated successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Xử lý lỗi validation
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            // Xử lý các ngoại lệ khác
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getAll()
    {
        try {
            // Thực hiện validation đối với các tham số nếu cần
            // Ví dụ: kiểm tra nếu có một tham số không mong muốn thì trả về lỗi 422
            $validator = Validator::make(request()->all(), [
                // Định nghĩa các quy tắc kiểm tra nếu cần
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Gọi phương thức trong repository để lấy danh sách người dùng
            $users = $this->userRepository->getAll();

            return response()->json([
                'status' => true,
                'data' => $users,
                'message' => 'Users found',
            ], 200);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ trong trường hợp có lỗi xảy ra
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $this->userRepository->findById($id);

        if ($user) {
            $this->userRepository->delete($user);
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
    }
}