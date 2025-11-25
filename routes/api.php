<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

// Tirando a possibilidade de se registrar sem auth, devido o pouco tempo sobrando para entrega.
// Route::post('/customers', [CustomerController::class, 'store']);

Route::middleware('jwt.auth')->group(function() {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('companies', CompanyController::class);

    Route::get('/user', [UserController::class, 'show']);

    Route::post('/file', [FileController::class, 'upload']);
    Route::get('/file', [FileController::class, 'show']);
    Route::delete('/file', [FileController::class, 'destroy']);

    Route::post('/user_company', [CompanyUserController::class, 'attach']);
    Route::delete('/user_company', [CompanyUserController::class, 'detach']);

    Route::post('/logout', [AuthController::class, 'logout']);
});