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

//get user(s)
Route::get('/users', [AuthenticationControllerTest::class, 'getAllUsers']);
Route::get('/users/{id}', [AuthenticationControllerTest::class, 'getUserById']);
