<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationControllerTest;
use App\Http\Controllers\BeneficiaryManagementController;
use App\Http\Controllers\BeneficiaryTransactionStatusController;


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
    //fetch beneficiaries for a specific payment period
    // Route::get('/beneficiaries', [BeneficiaryManagementController::class, 'getBeneficiariesByDistrict']);
    // generate unique transaction ids
    Route::get('/beneficiaries/update-transaction-ids', [BeneficiaryManagementController::class, 'generateTransactionIds']);
    // post rqst to get transaction statuses for beneficiaries
    Route::post('/beneficiary-transaction-status', [BeneficiaryTransactionStatusController::class, 'store']);
    // post rqst to get beneficiaries images
    Route::post('/beneficiary-images', [BeneficiaryTransactionStatusController::class, 'storeImage']);

    Route::get('/approved-payment-schools', [BeneficiaryManagementController::class, 'getApprovedPaymentSchools']);Route::middleware([App\Http\Middleware\LogActivity::class])->group(function () {
        Route::get('/beneficiaries', [BeneficiaryManagementController::class, 'getBeneficiariesByDistrict']);
        // Other routes within this middleware group
    });



});










