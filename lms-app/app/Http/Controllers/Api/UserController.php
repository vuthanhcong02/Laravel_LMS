<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        } catch (ValidationException $e) {
            // Handle validation error
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            // Handle other exceptions
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'data' => $this->userRepository->update($id, $request->all()),
            'message' => 'user updated successfully',
        ], 200);
    }

    public function getAll()
    {
        try {
            // Validate query parameters if needed
            // Example: check for unexpected parameters and return 422 if invalid
            $validator = Validator::make(request()->all(), [
                // Define validation rules if needed
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }

            // Call repository method to retrieve user list
            $users = $this->userRepository->getAll();

            return response()->json([
                'status' => true,
                'data' => $users,
                'message' => 'Users found',
            ], 200);
        } catch (Exception $e) {
            // Handle unexpected exception
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
            $this->userRepository->delete($id);

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
