<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/customers', [CustomerController::class, 'store']);

Route::middleware('jwt.auth')->group(function() {
    Route::apiResource('customers', CustomerController::class)->except(['store']);;
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('companies', CompanyController::class);

    Route::post('/files', [FileController::class, 'store']);
    Route::delete('/files/{user_id}', [FileController::class, 'destroy']);

    Route::post('/user_company', [CompanyUserController::class, 'attach']);
    Route::delete('/user_company', [CompanyUserController::class, 'detach']);

    Route::post('/logout', [AuthController::class, 'logout']);
});