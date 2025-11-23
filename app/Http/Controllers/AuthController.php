<?php

namespace App\Http\Controllers;

use App\Exceptions\InternalErrorException;
use Exception;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function logout(Request $request)
{
    try {
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json([
                'error' => 'Token not provided'
            ], 400);
        }

        JWTAuth::invalidate($token);

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    } catch (Exception $e) {
        throw new InternalErrorException();
    }
}
}
