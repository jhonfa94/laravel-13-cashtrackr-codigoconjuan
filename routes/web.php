<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/register', [RegisterController::class, "index"])->name("register");
Route::post('/auth/register', [RegisterController::class, "store"])->name("register.store");

Route::get('/auth/login', [LoginController::class, "index"])->name("login");
Route::post('/auth/login', [LoginController::class, "store"])->name("login.store");


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest$request) {
    $request->fulfill();
    return redirect()->route('dashboard')->with('success', 'Cuenta verificada exitosamente');
})
    ->middleware(['auth', 'signed', 'throttle:1,1'])
    ->name('verification.verify');

Route::get('email/verify', function () {
    return view("auth.verfify-email");
})->middleware(['auth'])->name('verification.notice');

Route::post("email/verification-notification", function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with("success", "Correo de verificación enviado exitosamente");
})->middleware(['auth', 'throttle:1,1'])->name('verification.send');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
