<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthenticationControllerTest extends Controller
{
    //register function
    public function register(Request $request)
    {
        // Validate the basic input first
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
            ], 422); // HTTP status code 422 Unprocessable Entity for validation errors
        }

        // Check if the user already exists by email
        $existingUserByEmail = User::where('email', $request->email)->first();
        if ($existingUserByEmail) {
            return response()->json([
                'message' => 'A user with this email already exists.'
            ], 409); // HTTP status code 409 Conflict
        }

        // Check if the phone number is already used
        $existingUserByPhone = User::where('phone_number', $request->phone_number)->first();
        if ($existingUserByPhone) {
            return response()->json([
                'message' => 'A user with this phone number already exists.'
            ], 409); // HTTP status code 409 Conflict
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

        // Check if the user exists with the given email
        $user = User::where('email', $request->email)->first();

        // If no user exists with the given email
        if (!$user) {
            return response()->json([
                'message' => 'No user found with this email address.'
            ], 404);  // Using 404 status code for "Not Found"
        }

        // Check if the password is correct
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password provided.'
            ], 401);  // Using 401 status code for "Unauthorized"
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => $user
        ], 200);
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
