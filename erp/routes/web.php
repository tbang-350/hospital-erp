<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryTransactionController;

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

// Inventory Management Routes
Route::resource('inventory', InventoryItemController::class);
Route::get('inventory/{inventoryItem}/stock-movement', [InventoryItemController::class, 'stockMovement'])->name('inventory.stock-movement');
Route::post('inventory/{inventoryItem}/stock-movement', [InventoryItemController::class, 'processStockMovement'])->name('inventory.process-stock-movement');
Route::get('inventory-reports/low-stock', [InventoryItemController::class, 'lowStock'])->name('inventory.low-stock');
Route::get('inventory-reports/expiring', [InventoryItemController::class, 'expiring'])->name('inventory.expiring');

// Inventory Transaction Routes
Route::resource('inventory-transactions', InventoryTransactionController::class)->except(['edit', 'update', 'destroy']);
Route::get('inventory/{inventoryItem}/transactions', [InventoryTransactionController::class, 'getItemTransactions'])->name('inventory.transactions');
Route::get('reports/stock-movement', [InventoryTransactionController::class, 'stockMovementReport'])->name('reports.stock-movement');
Route::get('reports/stock-movement/export', [InventoryTransactionController::class, 'exportStockMovement'])->name('reports.stock-movement.export');
