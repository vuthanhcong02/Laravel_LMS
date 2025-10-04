<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:255',
        ]);
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $data = [
            'sub' => auth()->user()->id,
            'random' => rand().time(),
            'exp' => time() + config('jwt.refresh_ttl'),
        ];
        $refeshToken = $this->createRefreshToken();

        return $this->respondWithToken($token, $refeshToken);
    }

    public function profile()
    {
        try {
            $user = auth()->user();

            return response()->json([
                'code' => 200,
                'message' => 'Success',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid token',
            ], 500);
        }
    }

    public function logout()
    {
        try {
            auth('api')->logout();

            return response()->json([
                'message' => 'Successfully logged out',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid refresh token',
            ], 500);
        }
    }

    public function refresh()
    {
        $refeshToken = request()->refresh_token;
        try {
            $decoded = JWTAuth::getJWTProvider()->decode($refeshToken);
            // Xử lí lấy lại token mới
            // Lấy thông tin user
            $user = User::find($decoded['user_id']);
            if (! $user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }
            // Khoi phuc token
            $token = auth()->login($user);
            // Tao token thay đổi
            $refeshToken = $this->createRefreshToken();

            return $this->respondWithToken($token, $refeshToken);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid refresh token',
            ], 500);
        }
    }

    private function respondWithToken($token, $refeshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refeshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    private function createRefreshToken()
    {
        $data = [
            'user_id' => auth()->user()->id,
            'random' => rand().time(),
            'exp' => time() + config('jwt.refresh_ttl'),
        ];
        $refeshToken = JWTAuth::getJWTProvider()->encode($data);

        return $refeshToken;
    }
}
