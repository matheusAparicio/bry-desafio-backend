<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('customers', CustomerController::class);
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('companies', CompanyController::class);

Route::post('/files', [FileController::class, 'store']);
Route::delete('/files/{user_id}', [FileController::class, 'destroy']);

Route::post('/user_company', [CompanyUserController::class, 'attach']);
Route::delete('/user_company', [CompanyUserController::class, 'detach']);


