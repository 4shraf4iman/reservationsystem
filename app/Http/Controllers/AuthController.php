<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);



            // Send email verification notification
            $user->sendEmailVerificationNotification();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully. Please check your email to verify your account.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'role' => $user->role,
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while registering the user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|string',
        'password' => 'required',
    ]);


    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Check if user exists with either username or email
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User  logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role' => $user->role,
            'user' => $user,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while logging in',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'User logged out successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while logging out',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
