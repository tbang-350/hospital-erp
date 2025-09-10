<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('landing');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/notifications', function () {
    return view('notifications');
})->name('notifications');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

Route::resource('patients', PatientController::class)->only(['index','create','store','show']);

// Appointment scheduling from patient profile
Route::get('/patients/{patient}/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

// Appointment lists and details
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');

// Invoice creation from patient profile
Route::get('/patients/{patient}/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');

// Invoice lists and details
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
