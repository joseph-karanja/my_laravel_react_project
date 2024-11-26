<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationControllerTest;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Registers a new user
Route::post('/register', [AuthenticationControllerTest::class, 'register']);
// Authenticates a user
Route::post('/login', [AuthenticationControllerTest::class, 'login']);  



// Authenticated Routes go here
Route::middleware('auth:sanctum')->group(function () {
    // Retrieve all users
    Route::get('/users', [AuthenticationControllerTest::class, 'getAllUsers']);

    // Retrieve a specific user by ID
    Route::get('/users/{id}', [AuthenticationControllerTest::class, 'getUserById']);
});





