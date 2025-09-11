<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\InventoryItem;
use App\Models\Invoice;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create notification for new patient
     */
    public static function createPatientAddedNotification(Patient $patient)
    {
        return Notification::create([
            'type' => Notification::TYPE_PATIENT_ADDED,
            'title' => 'New Patient Added',
            'message' => "New patient {$patient->first_name} {$patient->last_name} (MRN: {$patient->mrn}) has been registered.",
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => 1, // For now, will be dynamic when auth is implemented
            'related_type' => 'App\\Models\\Patient',
            'related_id' => $patient->id,
            'priority' => Notification::PRIORITY_MEDIUM,
            'action_url' => route('patients.show', $patient->id),
            'data' => [
                'patient_name' => $patient->first_name . ' ' . $patient->last_name,
                'mrn' => $patient->mrn,
                'created_at' => $patient->created_at->toISOString()
            ]
        ]);
    }

    /**
     * Create notification for new appointment
     */
    public static function createAppointmentNotification(Appointment $appointment)
    {
        $appointment->load('patient');
        
        return Notification::create([
            'type' => Notification::TYPE_APPOINTMENT_CREATED,
            'title' => 'New Appointment Scheduled',
            'message' => "Appointment scheduled for {$appointment->patient->first_name} {$appointment->patient->last_name} on {$appointment->scheduled_at->format('M d, Y \\a\\t g:i A')}.",
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => 1,
            'related_type' => 'App\\Models\\Appointment',
            'related_id' => $appointment->id,
            'priority' => Notification::PRIORITY_MEDIUM,
            'action_url' => route('appointments.show', $appointment->id),
            'data' => [
                'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                'scheduled_at' => $appointment->scheduled_at->toISOString(),
                'reason' => $appointment->reason
            ]
        ]);
    }

    /**
     * Create notification for appointment reminder
     */
    public static function createAppointmentReminderNotification(Appointment $appointment)
    {
        $appointment->load('patient');
        
        return Notification::create([
            'type' => Notification::TYPE_APPOINTMENT_REMINDER,
            'title' => 'Upcoming Appointment',
            'message' => "Appointment with {$appointment->patient->first_name} {$appointment->patient->last_name} is scheduled for {$appointment->scheduled_at->format('g:i A')} today.",
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => 1,
            'related_type' => 'App\\Models\\Appointment',
            'related_id' => $appointment->id,
            'priority' => Notification::PRIORITY_HIGH,
            'action_url' => route('appointments.show', $appointment->id),
            'data' => [
                'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                'scheduled_at' => $appointment->scheduled_at->toISOString(),
                'time_until' => $appointment->scheduled_at->diffForHumans()
            ]
        ]);
    }

    /**
     * Create notification for low stock items
     */
    public static function createLowStockNotification(InventoryItem $item)
    {
        return Notification::create([
            'type' => Notification::TYPE_INVENTORY_LOW_STOCK,
            'title' => 'Low Stock Alert',
            'message' => "Item '{$item->name}' is running low. Current stock: {$item->quantity} {$item->uom}. Reorder level: {$item->reorder_level}.",
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => 1,
            'related_type' => 'App\\Models\\InventoryItem',
            'related_id' => $item->id,
            'priority' => Notification::PRIORITY_HIGH,
            'action_url' => route('inventory.show', $item->id),
            'data' => [
                'item_name' => $item->name,
                'current_stock' => $item->quantity,
                'reorder_level' => $item->reorder_level,
                'uom' => $item->uom
            ]
        ]);
    }

    /**
     * Create notification for expired items
     */
    public static function createExpiredItemNotification(InventoryItem $item)
    {
        if (self::shouldCreateNotification('expired_items', $item->id)) {
            Notification::create([
                'type' => Notification::TYPE_INVENTORY_EXPIRED,
                'title' => 'Expired Item Alert',
                'message' => "Item '{$item->name}' has expired and should be removed from inventory.",
                'data' => json_encode([
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'expiry_date' => $item->expiry_date,
                ]),
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => 1, // Admin user
                'related_type' => 'App\\Models\\InventoryItem',
                'related_id' => $item->id,
                'priority' => Notification::PRIORITY_HIGH,
                'action_url' => route('inventory.show', $item->id),
            ]);
        }
    }

    /**
     * Create notification for items expiring soon
     */
    public static function createExpiringSoonNotification(InventoryItem $item)
    {
        if (self::shouldCreateNotification('expiring_soon', $item->id)) {
            $daysUntilExpiry = now()->diffInDays($item->expiry_date);
            
            Notification::create([
                'type' => Notification::TYPE_INVENTORY_EXPIRED,
                'title' => 'Item Expiring Soon',
                'message' => "Item '{$item->name}' will expire in {$daysUntilExpiry} day(s). Please check inventory.",
                'data' => json_encode([
                    'item_id' => $item->id,
                    'item_name' => $item->name,
                    'expiry_date' => $item->expiry_date,
                    'days_until_expiry' => $daysUntilExpiry,
                ]),
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => 1, // Admin user
                'related_type' => 'App\\Models\\InventoryItem',
                'related_id' => $item->id,
                'priority' => Notification::PRIORITY_MEDIUM,
                'action_url' => route('inventory.show', $item->id),
            ]);
        }
    }

    /**
     * Create notification for appointments starting soon
     */
    public static function createAppointmentStartingSoonNotification(Appointment $appointment)
    {
        if (self::shouldCreateNotification('appointment_starting_soon', $appointment->id)) {
            Notification::create([
                'type' => Notification::TYPE_APPOINTMENT_REMINDER,
                'title' => 'Appointment Starting Soon',
                'message' => "Appointment with {$appointment->patient->first_name} {$appointment->patient->last_name} is starting soon at {$appointment->scheduled_at->format('g:i A')}.",
                'data' => json_encode([
                    'appointment_id' => $appointment->id,
                    'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                    'scheduled_at' => $appointment->scheduled_at->toISOString(),
                ]),
                'notifiable_type' => 'App\\Models\\User',
                'notifiable_id' => 1, // Admin user
                'related_type' => 'App\\Models\\Appointment',
                'related_id' => $appointment->id,
                'priority' => Notification::PRIORITY_HIGH,
                'action_url' => route('appointments.show', $appointment->id),
            ]);
        }
    }

    /**
     * Check if a notification should be created (to prevent spam)
     */
    private static function shouldCreateNotification($type, $relatedId)
    {
        $hours = match($type) {
            'expired_items' => 24,
            'expiring_soon' => 72,
            'low_stock' => 24,
            'appointment_reminder' => 24,
            'appointment_starting_soon' => 2,
            default => 24
        };

        return !Notification::where('related_id', $relatedId)
            ->where('created_at', '>=', now()->subHours($hours))
            ->exists();
    }

    /**
     * Create notification for new invoice
     */
    public static function createInvoiceNotification(Invoice $invoice)
    {
        $invoice->load('patient');
        
        return Notification::create([
            'type' => Notification::TYPE_INVOICE_CREATED,
            'title' => 'New Invoice Created',
            'message' => "Invoice {$invoice->invoice_number} created for {$invoice->patient->first_name} {$invoice->patient->last_name}. Amount: {$invoice->currency} {$invoice->total}.",
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => 1,
            'related_type' => 'App\\Models\\Invoice',
            'related_id' => $invoice->id,
            'priority' => Notification::PRIORITY_MEDIUM,
            'action_url' => route('invoices.show', $invoice->id),
            'data' => [
                'invoice_number' => $invoice->invoice_number,
                'patient_name' => $invoice->patient->first_name . ' ' . $invoice->patient->last_name,
                'amount' => $invoice->total,
                'currency' => $invoice->currency
            ]
        ]);
    }

    /**
     * Check and create notifications for items expiring soon
     */
    public static function checkExpiringItems($days = 30)
    {
        $expiringItems = InventoryItem::where('expiry_date', '<=', now()->addDays($days))
                                    ->where('expiry_date', '>', now())
                                    ->get();

        foreach ($expiringItems as $item) {
            // Check if notification already exists for this item
            $existingNotification = Notification::where('type', Notification::TYPE_INVENTORY_EXPIRED)
                                                ->where('related_type', 'App\\Models\\InventoryItem')
                                                ->where('related_id', $item->id)
                                                ->where('created_at', '>=', now()->subDays(7)) // Don't spam notifications
                                                ->exists();

            if (!$existingNotification) {
                self::createExpiredItemNotification($item);
            }
        }
    }

    /**
     * Check and create notifications for low stock items
     */
    public static function checkLowStockItems()
    {
        $lowStockItems = InventoryItem::whereRaw('quantity <= reorder_level')->get();

        foreach ($lowStockItems as $item) {
            // Check if notification already exists for this item
            $existingNotification = Notification::where('type', Notification::TYPE_INVENTORY_LOW_STOCK)
                                                ->where('related_type', 'App\\Models\\InventoryItem')
                                                ->where('related_id', $item->id)
                                                ->where('created_at', '>=', now()->subDays(3)) // Don't spam notifications
                                                ->exists();

            if (!$existingNotification) {
                self::createLowStockNotification($item);
            }
        }
    }

    /**
     * Check and create notifications for upcoming appointments
     */
    public static function checkUpcomingAppointments($hours = 2)
    {
        $upcomingAppointments = Appointment::where('scheduled_at', '>=', now())
                                          ->where('scheduled_at', '<=', now()->addHours($hours))
                                          ->where('status', 'scheduled')
                                          ->get();

        foreach ($upcomingAppointments as $appointment) {
            // Check if reminder notification already exists
            $existingNotification = Notification::where('type', Notification::TYPE_APPOINTMENT_REMINDER)
                                                ->where('related_type', 'App\\Models\\Appointment')
                                                ->where('related_id', $appointment->id)
                                                ->where('created_at', '>=', now()->subHours(6))
                                                ->exists();

            if (!$existingNotification) {
                self::createAppointmentReminderNotification($appointment);
            }
        }
    }
}