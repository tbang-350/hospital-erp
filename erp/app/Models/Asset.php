<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Asset extends Model
{
    protected $fillable = [
        'tag',
        'name',
        'category',
        'serial_number',
        'purchase_date',
        'warranty_expiry',
        'purchase_cost',
        'location',
        'meta',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_cost' => 'decimal:2',
        'meta' => 'array',
    ];

    /**
     * Get maintenance orders for this asset
     */
    public function maintenanceOrders()
    {
        return $this->hasMany(MaintenanceOrder::class);
    }

    /**
     * Get warranty status
     */
    public function getWarrantyStatusAttribute()
    {
        if (!$this->warranty_expiry) {
            return 'No warranty info';
        }

        $now = Carbon::now();
        $expiry = Carbon::parse($this->warranty_expiry);

        if ($expiry->isPast()) {
            return 'Expired';
        }

        $daysLeft = $now->diffInDays($expiry);
        
        if ($daysLeft <= 30) {
            return 'Expiring soon';
        }

        return 'Active';
    }

    /**
     * Get asset age in years
     */
    public function getAgeAttribute()
    {
        if (!$this->purchase_date) {
            return null;
        }

        return Carbon::parse($this->purchase_date)->diffInYears(Carbon::now());
    }

    /**
     * Scope for assets with expiring warranties
     */
    public function scopeExpiringWarranty($query, $days = 30)
    {
        return $query->whereNotNull('warranty_expiry')
                    ->where('warranty_expiry', '>', now())
                    ->where('warranty_expiry', '<=', now()->addDays($days));
    }

    /**
     * Scope for assets by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
