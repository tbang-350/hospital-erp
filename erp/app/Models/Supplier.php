<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'contact_person', 'email', 'phone', 
        'address', 'tax_id', 'status', 'notes'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'supplier_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
