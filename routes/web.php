<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('auth');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('auth');
});



//Login & Signup Routes
Route::get('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register');
Route::post('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register');

Route::get('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');