<?php

use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Patient\MessageController;
use App\Http\Controllers\Patient\PaymentController;
use Faker\Provider\Payment;
use App\Http\Controllers\Patient\AppointmentreviewController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\ConsultationController;

//Login & Signup Routes
Route::get('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register');
Route::post('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register');

Route::get('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');


//patient routes
Route::get('/', function () {
        return redirect()->route('patient.dashboard.index');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');

    //patient routes
    Route::get('/patient/dashboard', [DashboardController::class, 'index'])->name('patient.dashboard.index');
    Route::get('/patient/appointments', [AppointmentController::class, 'index'])->name('patient.appointments.index');
    Route::post('/patient/appointments', [AppointmentController::class, 'store'])->name('patient.appointments.store');
    Route::get('/patient/chat/{doctor}', [MessageController::class, 'showDoctorMsg'])->name('patients.messages.show');
    Route::post('/patient/chat/{doctor}', [MessageController::class, 'storeDoctorMsg'])->name('patients.messages.store');
    Route::get('/patient/doctors', [App\Http\Controllers\Patient\DoctorController::class, 'index'])->name('patient.doctors.index');

    //stripe routes start
    Route::post('/appointments/pay', [PaymentController::class, 'pay'])->name('patient.appointments.pay');
    Route::get('/appointments/success/{id}', [PaymentController::class, 'success'])->name('patient.appointments.success');
    //stripe routes end

    Route::post('/appointments/review', [AppointmentreviewController::class, 'store'])->name('patient.appointments.review.store');


    //doctor routes
    Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');


    //common routes
    Route::get('/consultation/{appointment}/doctor', [ConsultationController::class, 'index'])->name('doctor.consultation');
    Route::get('/consultation/{appointment}/patient', [ConsultationController::class, 'index'])->name('patient.consultation');
});
