<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'role' => 'sometimes|in:admin,player',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role ?? 'player',
            'is_active' => true,
        ]);

        // Create token manually for MongoDB compatibility
        $plainTextToken = Str::random(40);
        $hashedToken = hash('sha256', $plainTextToken);
        
        $accessToken = new PersonalAccessToken([
            'tokenable_type' => get_class($user),
            'tokenable_id' => (string) $user->_id,
            'name' => 'auth_token',
            'token' => $hashedToken,
            'abilities' => ['*'],
        ]);
        $accessToken->save();
        
        // Find the token we just created by hash to get the _id
        $savedToken = PersonalAccessToken::where('token', $hashedToken)->first();

        // Return token in Sanctum format: {id}|{plainTextToken}
        $tokenId = (string) $savedToken->_id;
        $token = $tokenId . '|' . $plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Account is inactive'], 403);
        }

        // Create token manually for MongoDB compatibility
        $plainTextToken = Str::random(40);
        $hashedToken = hash('sha256', $plainTextToken);
        
        $accessToken = new PersonalAccessToken([
            'tokenable_type' => get_class($user),
            'tokenable_id' => (string) $user->_id,
            'name' => 'auth_token',
            'token' => $hashedToken,
            'abilities' => ['*'],
        ]);
        $accessToken->save();
        
        // Find the token we just created by hash to get the _id
        $savedToken = PersonalAccessToken::where('token', $hashedToken)->first();

        // Return token in Sanctum format: {id}|{plainTextToken}
        $tokenId = (string) $savedToken->_id;
        $token = $tokenId . '|' . $plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Revoke all tokens for the user
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}

