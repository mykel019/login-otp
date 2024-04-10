<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;

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

Route::get('/', function () {
    return redirect('login');
});

Route::middleware(['guest'])->group(function(){
  Route::get('/login', [AuthController::class, 'login'])->name('login');
  Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
  Route::get('one-time-password', [AuthController::class, 'otp'])->name('otp');
  Route::post('validate-otp', [AuthController::class, 'validateOtp'])->name('validateOtp');
  Route::post('resend-otp', [AuthController::class, 'resendOTP'])->name('resend-otp');

  Route::get('/show-forgot-password', [ForgotPasswordController::class, 'showforgotPassword'])->name('show-forgot-password');
  Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password');
  Route::get('/reset-password/{email}', [ForgotPasswordController::class, 'showResetPassword'])->name('reset-password');
  Route::post('/update-password', [ForgotPasswordController::class, 'updatePassword'])->name('updatepassword');

  Route::get('/signup', [SignUpController::class, 'signUp'])->name('signup');
  Route::post('/register', [SignUpController::class, 'register'])->name('register');
});


Route::middleware(['auth'])->group(function(){
  Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::get('/get-credentials', function(){
  dd(session('credentials'));
});

Route::get('logout', function(){
  Session::flush();
  Auth::logout();
  return redirect('login');
});

