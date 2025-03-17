<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home page route
Route::get('/', function () {
    return view('welcome');
})->middleware(['auth', 'verified']); // Ensure user is authenticated and email verified to view the home page

// Forgot and reset password routes
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handle email verification process
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // Mark the user's email as verified
    return redirect('/'); // Redirect to the home page after verification
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Route to display the welcome page for non-authenticated users
Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/test-error', function () {
    throw new Exception('This is a test error for logging.');
});