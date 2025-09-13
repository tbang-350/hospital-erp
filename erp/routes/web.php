<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AssetController;

Route::get('/', function () {
    return view('landing');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');

// Notification Routes
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
Route::get('/notifications/{notification}/read', [NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.read');
Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');

// Test notification route for demonstration
Route::get('/test-notifications', function () {
    $notifications = [
        [
            'title' => 'New Patient Registration',
            'message' => 'John Doe has been registered as a new patient.',
            'type' => 'patient_registered',
            'priority' => 'medium',
            'data' => ['patient_id' => 1]
        ],
        [
            'title' => 'Urgent: Low Stock Alert',
            'message' => 'Paracetamol stock is critically low (2 units remaining).',
            'type' => 'low_stock',
            'priority' => 'high',
            'data' => ['item_id' => 1]
        ],
        [
            'title' => 'Appointment Reminder',
            'message' => 'Patient appointment scheduled for tomorrow at 10:00 AM.',
            'type' => 'appointment_reminder',
            'priority' => 'medium',
            'data' => ['appointment_id' => 1]
        ],
        [
            'title' => 'Critical: Equipment Maintenance',
            'message' => 'X-Ray machine requires immediate maintenance.',
            'type' => 'equipment_maintenance',
            'priority' => 'high',
            'data' => ['equipment_id' => 1]
        ],
        [
            'title' => 'Invoice Generated',
            'message' => 'Invoice #INV-001 has been generated for patient John Doe.',
            'type' => 'invoice_generated',
            'priority' => 'low',
            'data' => ['invoice_id' => 1]
        ]
    ];
    
    foreach ($notifications as $notificationData) {
        \App\Models\Notification::create([
            'title' => $notificationData['title'],
            'message' => $notificationData['message'],
            'type' => $notificationData['type'],
            'priority' => $notificationData['priority'],
            'data' => json_encode($notificationData['data']),
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    return redirect()->route('dashboard')->with('success', 'Test notifications created! Check the notification bell to see the enhanced visual feedback.');
})->name('test.notifications');

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
Route::get('inventory/{inventory}/stock-movement', [InventoryItemController::class, 'stockMovement'])->name('inventory.stock-movement');

// Supplier Management Routes
Route::resource('suppliers', SupplierController::class);
Route::post('inventory/{inventory}/stock-movement', [InventoryItemController::class, 'processStockMovement'])->name('inventory.process-stock-movement');
Route::get('inventory-reports/low-stock', [InventoryItemController::class, 'lowStock'])->name('inventory.low-stock');
Route::get('inventory-reports/expiring', [InventoryItemController::class, 'expiring'])->name('inventory.expiring');

// Inventory Transaction Routes
Route::resource('inventory-transactions', InventoryTransactionController::class)->except(['edit', 'update', 'destroy']);
Route::get('inventory/{inventoryItem}/transactions', [InventoryTransactionController::class, 'getItemTransactions'])->name('inventory.transactions');
Route::get('reports/stock-movement', [InventoryTransactionController::class, 'stockMovementReport'])->name('reports.stock-movement');
Route::get('reports/stock-movement/export', [InventoryTransactionController::class, 'exportStockMovement'])->name('reports.stock-movement.export');

// HR & Payroll Management Routes
Route::resource('employees', EmployeeController::class);
Route::resource('payrolls', PayrollController::class);
Route::patch('payrolls/{payroll}/mark-paid', [PayrollController::class, 'markAsPaid'])->name('payrolls.mark-paid');
Route::post('payrolls/bulk-generate', [PayrollController::class, 'generateBulkPayroll'])->name('payrolls.bulk-generate');

// Asset Management Routes
Route::resource('assets', AssetController::class);
