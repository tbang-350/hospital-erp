<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InventoryItem extends Model
{
    protected $fillable = [
        'sku', 'name', 'category', 'quantity', 'reorder_level', 
        'expiry_date', 'unit_cost', 'uom'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_cost' => 'decimal:2'
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            if (empty($item->sku)) {
                $item->sku = 'SKU-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
            }
        });
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->reorder_level;
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= $days;
    }
}
