<?php

use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Patient\MessageController;
use App\Http\Controllers\Patient\PaymentController;
use App\Http\Controllers\Patient\SymptomCheckerController;
use Faker\Provider\Payment;
use App\Http\Controllers\Patient\AppointmentreviewController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\MessageController as DoctorMessageController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

//Login & Signup Routes
Route::get('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register.show');
Route::post('/register', [App\Http\Controllers\LoginController::class, 'register'])->name('register');

Route::get('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login.show');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('logout');


//patient routes
Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('user.index');
        }
        return redirect()->route('login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');

    Route::group(['middleware' => ['role:patient']], function () {
        Route::get('/patient/dashboard', [DashboardController::class, 'index'])->name('patient.dashboard.index');
        Route::get('/patient/appointments', [AppointmentController::class, 'index'])->name('patient.appointments.index');
        Route::post('/patient/appointments', [AppointmentController::class, 'store'])->name('patient.appointments.store');
        Route::patch('/patient/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('patient.appointments.cancel');
        Route::get('/patient/doctors', [App\Http\Controllers\Patient\DoctorController::class, 'index'])->name('patient.doctors.index');
        Route::match(['get', 'post'], '/patient/symptom-checker', [SymptomCheckerController::class, 'index'])->name('patient.symptom-checker.index');
        Route::get('/patient/messages', [MessageController::class, 'index'])->name('patient.messages.index');
        Route::get('/patient/chat/{doctor}', [MessageController::class, 'showDoctorMsg'])->name('patient.messages.show');
        Route::post('/patient/chat/{doctor}', [MessageController::class, 'storeDoctorMsg'])->name('patient.messages.store');

        // stripe routes
        Route::post('/appointments/pay', [PaymentController::class, 'pay'])->name('patient.appointments.pay');
        Route::get('/appointments/success/{id}', [PaymentController::class, 'success'])->name('patient.appointments.success');

        Route::post('/appointments/review', [AppointmentreviewController::class, 'store'])->name('patient.appointments.review.store');
    });

    Route::group(['middleware' => ['role:doctor']], function () {
        Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        Route::get('/doctor/appointments', [DoctorAppointmentController::class, 'index'])->name('doctor.appointments.index');
        Route::get('/doctor/appointments/{id}', [DoctorAppointmentController::class, 'show'])->name('doctor.appointments.show');
        Route::patch('/doctor/appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('doctor.appointments.status');
        Route::get('/doctor/messages', [DoctorMessageController::class, 'index'])->name('doctor.messages.index');
        Route::get('/doctor/chat/{patient}', [DoctorMessageController::class, 'showPatientMsg'])->name('doctor.messages.show');
        Route::post('/doctor/chat/{patient}', [DoctorMessageController::class, 'storePatientMsg'])->name('doctor.messages.store');
    });


    //common routes
    Route::get('/consultation/{appointment}/doctor', [ConsultationController::class, 'index'])->name('doctor.consultation');
    Route::get('/consultation/{appointment}/patient', [ConsultationController::class, 'index'])->name('patient.consultation');

    Route::get('/appointments/{appointment}/prescription/create', [PrescriptionController::class, 'create'])
    ->name('doctor.prescription.create');

    // ===== ADMIN ROUTES =====
    Route::group(['middleware' => ['auth', 'role:admin']], function () {
        // Dashboard
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Doctors Management
        Route::get('/admin/doctors', [AdminDashboardController::class, 'doctors'])->name('admin.doctors.index');
        Route::get('/admin/doctors/create', [AdminDashboardController::class, 'doctorCreate'])->name('admin.doctors.create');
        Route::post('/admin/doctors', [AdminDashboardController::class, 'doctorStore'])->name('admin.doctors.store');
        Route::get('/admin/doctors/{id}/edit', [AdminDashboardController::class, 'doctorEdit'])->name('admin.doctors.edit');
        Route::put('/admin/doctors/{id}', [AdminDashboardController::class, 'doctorUpdate'])->name('admin.doctors.update');
        Route::delete('/admin/doctors/{id}', [AdminDashboardController::class, 'doctorDestroy'])->name('admin.doctors.destroy');
        Route::get('/admin/doctors/{id}', [AdminDashboardController::class, 'doctorShow'])->name('admin.doctors.show');

        // Patients Management
        Route::get('/admin/patients', [AdminDashboardController::class, 'patients'])->name('admin.patients.index');
        Route::get('/admin/patients/{id}', [AdminDashboardController::class, 'patientShow'])->name('admin.patients.show');

        // Appointments Management
        Route::get('/admin/appointments', [AdminDashboardController::class, 'appointments'])->name('admin.appointments.index');
        Route::put('/admin/appointments/{id}/status', [AdminDashboardController::class, 'updateAppointmentStatus'])->name('admin.appointments.updateStatus');

        // Categories
        Route::get('/admin/categories', [AdminDashboardController::class, 'categories'])->name('admin.categories.index');
        Route::get('/admin/categories/create', [AdminDashboardController::class, 'categoryCreate'])->name('admin.categories.create');
        Route::post('/admin/categories', [AdminDashboardController::class, 'categoryStore'])->name('admin.categories.store');
        Route::get('/admin/categories/{id}/edit', [AdminDashboardController::class, 'categoryEdit'])->name('admin.categories.edit');
        Route::put('/admin/categories/{id}', [AdminDashboardController::class, 'categoryUpdate'])->name('admin.categories.update');
        Route::delete('/admin/categories/{id}', [AdminDashboardController::class, 'categoryDestroy'])->name('admin.categories.destroy');

        // Analytics
        Route::get('/admin/analytics', [AdminDashboardController::class, 'analytics'])->name('admin.analytics.index');
    });

    // Appointment Show - accessible by all authenticated users
    Route::get('/appointments/{id}', [AdminDashboardController::class, 'appointmentShow'])->name('admin.appointments.show');
});
