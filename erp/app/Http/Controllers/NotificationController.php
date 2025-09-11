<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display all notifications page
     */
    public function index(Request $request)
    {
        $query = Notification::where('notifiable_type', 'App\\Models\\User')
                            ->where('notifiable_id', 1) // For now using user ID 1, will be Auth::id() when auth is implemented
                            ->with('related')
                            ->latest();

        // Filter by type if specified
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        $notifications = $query->paginate(20);
        
        return view('notifications', compact('notifications'));
    }

    /**
     * Get unread notifications for dropdown (AJAX)
     */
    public function getUnread()
    {
        $notifications = Notification::where('notifiable_type', 'App\\Models\\User')
                                   ->where('notifiable_id', 1) // For now using user ID 1
                                   ->unread()
                                   ->latest()
                                   ->limit(10)
                                   ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * Mark notification as read and redirect to appropriate page
     */
    public function markAsReadAndRedirect(Notification $notification)
    {
        $notification->markAsRead();
        
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }
        
        // Default redirect based on notification type
        return $this->getDefaultRedirect($notification);
    }

    /**
     * Mark notification as read (AJAX)
     */
    public function markAsRead(Notification $notification)
    {
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notification::where('notifiable_type', 'App\\Models\\User')
                   ->where('notifiable_id', 1) // For now using user ID 1
                   ->unread()
                   ->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get default redirect URL based on notification type
     */
    private function getDefaultRedirect(Notification $notification)
    {
        return match($notification->type) {
            Notification::TYPE_PATIENT_ADDED => $notification->related_id 
                ? route('patients.show', $notification->related_id) 
                : route('patients.index'),
            Notification::TYPE_APPOINTMENT_CREATED, 
            Notification::TYPE_APPOINTMENT_REMINDER => $notification->related_id 
                ? route('appointments.show', $notification->related_id) 
                : route('appointments.index'),
            Notification::TYPE_INVENTORY_LOW_STOCK, 
            Notification::TYPE_INVENTORY_EXPIRED => $notification->related_id 
                ? route('inventory.show', $notification->related_id) 
                : route('inventory.index'),
            Notification::TYPE_INVOICE_CREATED, 
            Notification::TYPE_INVOICE_OVERDUE => $notification->related_id 
                ? route('invoices.show', $notification->related_id) 
                : route('invoices.index'),
            default => route('dashboard')
        };
    }
}
