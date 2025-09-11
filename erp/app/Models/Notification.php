<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'notifiable_type',
        'notifiable_id',
        'related_type',
        'related_id',
        'priority',
        'action_url'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    // Notification types
    const TYPE_PATIENT_ADDED = 'patient_added';
    const TYPE_APPOINTMENT_CREATED = 'appointment_created';
    const TYPE_APPOINTMENT_REMINDER = 'appointment_reminder';
    const TYPE_INVENTORY_LOW_STOCK = 'inventory_low_stock';
    const TYPE_INVENTORY_EXPIRED = 'inventory_expired';
    const TYPE_INVOICE_CREATED = 'invoice_created';
    const TYPE_INVOICE_OVERDUE = 'invoice_overdue';

    // Priority levels
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Get the notifiable entity (usually User)
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Get the related entity (Patient, Appointment, etc.)
     */
    public function related()
    {
        return $this->morphTo();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Check if notification is read
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for specific priority
     */
    public function scopeOfPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            self::PRIORITY_URGENT => 'red',
            self::PRIORITY_HIGH => 'orange',
            self::PRIORITY_MEDIUM => 'yellow',
            self::PRIORITY_LOW => 'blue',
            default => 'gray'
        };
    }

    /**
     * Get notification icon based on type
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            self::TYPE_PATIENT_ADDED => 'user-plus',
            self::TYPE_APPOINTMENT_CREATED => 'calendar-plus',
            self::TYPE_APPOINTMENT_REMINDER => 'clock',
            self::TYPE_INVENTORY_LOW_STOCK => 'exclamation-triangle',
            self::TYPE_INVENTORY_EXPIRED => 'x-circle',
            self::TYPE_INVOICE_CREATED => 'document-text',
            self::TYPE_INVOICE_OVERDUE => 'exclamation-circle',
            default => 'bell'
        };
    }

    // Helper methods for blade templates
    public function getIconBackgroundClass()
    {
        return match($this->type) {
            self::TYPE_PATIENT_ADDED => 'bg-blue-100 dark:bg-blue-900',
            self::TYPE_APPOINTMENT_CREATED => 'bg-green-100 dark:bg-green-900',
            self::TYPE_APPOINTMENT_REMINDER => 'bg-yellow-100 dark:bg-yellow-900',
            self::TYPE_INVENTORY_LOW_STOCK => 'bg-red-100 dark:bg-red-900',
            self::TYPE_INVENTORY_EXPIRED => 'bg-orange-100 dark:bg-orange-900',
            self::TYPE_INVOICE_CREATED => 'bg-purple-100 dark:bg-purple-900',
            self::TYPE_INVOICE_OVERDUE => 'bg-red-100 dark:bg-red-900',
            default => 'bg-gray-100 dark:bg-gray-700'
        };
    }

    public function getIconColorClass()
    {
        return match($this->type) {
            self::TYPE_PATIENT_ADDED => 'text-blue-600 dark:text-blue-400',
            self::TYPE_APPOINTMENT_CREATED => 'text-green-600 dark:text-green-400',
            self::TYPE_APPOINTMENT_REMINDER => 'text-yellow-600 dark:text-yellow-400',
            self::TYPE_INVENTORY_LOW_STOCK => 'text-red-600 dark:text-red-400',
            self::TYPE_INVENTORY_EXPIRED => 'text-orange-600 dark:text-orange-400',
            self::TYPE_INVOICE_CREATED => 'text-purple-600 dark:text-purple-400',
            self::TYPE_INVOICE_OVERDUE => 'text-red-600 dark:text-red-400',
            default => 'text-gray-600 dark:text-gray-400'
        };
    }

    public function getIconPath()
    {
        return match($this->type) {
            self::TYPE_PATIENT_ADDED => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
            self::TYPE_APPOINTMENT_CREATED => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5m-18 0h18',
            self::TYPE_APPOINTMENT_REMINDER => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            self::TYPE_INVENTORY_LOW_STOCK => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
            self::TYPE_INVENTORY_EXPIRED => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
            self::TYPE_INVOICE_CREATED => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            self::TYPE_INVOICE_OVERDUE => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
            default => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0'
        };
    }

    public function getPriorityBadgeClass()
    {
        return match($this->priority) {
            self::PRIORITY_URGENT => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            self::PRIORITY_HIGH => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            self::PRIORITY_MEDIUM => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            self::PRIORITY_LOW => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
        };
    }

    public function getPriorityDotClass()
    {
        return match($this->priority) {
            self::PRIORITY_URGENT => 'bg-red-500',
            self::PRIORITY_HIGH => 'bg-orange-500',
            self::PRIORITY_MEDIUM => 'bg-yellow-500',
            self::PRIORITY_LOW => 'bg-blue-500',
            default => 'bg-blue-500'
        };
    }
}