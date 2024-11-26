<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserActivityLog;
use App\Models\Token;
use Carbon\Carbon; 

class AuthenticationControllerTest extends Controller
{
    //register function
    public function register(Request $request)
    {
        // Validate the basic input 
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|email',
            'phone_number' => 'required|string',
            'password' => 'required|string|min:4',
        ]);

        // Check for password confirmation manually
        if ($request->password !== $request->password_confirmation) {
            return response()->json([
                'message' => 'Passwords do not match.'
            ], 422);
        }

        // Check if the user already exists by email
        $existingUserByEmail = User::where('email', $request->email)->first();
        if ($existingUserByEmail) {
            return response()->json([
                'message' => 'A user with this email already exists.'
            ], 409);
        }

        // Check if the phone number is already used
        $existingUserByPhone = User::where('phone_number', $request->phone_number)->first();
        if ($existingUserByPhone) {
            return response()->json([
                'message' => 'A user with this phone number already exists.'
            ], 409);
        }

        // Create and save the user if all checks pass
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    //login function
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        // Check if the user with the given email exists
        if (!$user) {
            return response()->json(['message' => 'No account found with that email.'], 404);
        }
    
        // Check if the provided password is correct
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Incorrect password.'], 401);
        }
    
        // Generate a token
        $tokenResult = $user->createToken('Personal Access Token');
        $plainTextToken = $tokenResult->plainTextToken;
    
        // Store token details
        $token = new Token([
            'user_uuid' => $user->uuid,
            'token' => $plainTextToken,
            'expires_at' => now()->addHours(24)
        ]);
        $token->save();
    
        // Log activity
        UserActivityLog::create([
            'user_uuid' => $user->uuid,
            'activity_type' => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'created_at' => now()
        ]);
    
        return response()->json([
            'access_token' => $plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }
    
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
        return response()->json($user);
    }
    
    

    
}
