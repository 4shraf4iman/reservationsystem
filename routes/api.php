<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;




//admin controller
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


//reservation contorller
Route::post('/registrations', [RegistrationController::class, 'store']);
Route::get('/registrations', [RegistrationController::class, 'index']);
Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
Route::get('/registrations/phone/{id}', [RegistrationController::class, 'showBySearchTerm']);
Route::post('/registrations/{id}', [RegistrationController::class, 'update']);
Route::delete('/registrations/{id}', [RegistrationController::class, 'destroy']);
