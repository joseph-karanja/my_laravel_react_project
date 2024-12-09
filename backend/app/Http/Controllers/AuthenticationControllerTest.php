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
            'email' => 'required|string',
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
            'Email' => 'required|string',
            'Password' => 'required|string'
        ]);

         // Validate the input using PascalCase fields
        $validatedData = $request->validate([
            'Email' => 'required|string',
            'Password' => 'required|string'
        ]);
    
        // $user = User::where('email', $request->email)->first();
        $user = User::where('email', $validatedData['Email'])->first();
    
        if (!$user) {
            return response()->json(['Message' => 'No account found with that email.'], 404);
        }
    
        if (!$user->is_active) {
            return response()->json(['Message' => 'Your account is inactive. Please contact system admin for assistance.'], 403);
        }
    
        if (!Hash::check($validatedData['Password'], $user->password)) {
            return response()->json(['Message' => 'Incorrect password.'], 401);
        }
    
        // Generate a token
        $tokenResult = $user->createToken('Personal Access Token');
        $plainTextToken = $tokenResult->plainTextToken;
    
        // Extract token string after the pipe
        $tokenParts = explode('|', $plainTextToken);
        $actualTokenString = $tokenParts[1];  // Get the part after the ID
    
        // Store token details
        $token = new Token([
            'user_uuid' => $user->uuid,
            'token' => $actualTokenString,  // Store the actual token string without the ID
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
            'access_token' => $actualTokenString,  // Return the token without the ID
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
