<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereRaw('quantity <= reorder_level');
                    break;
                case 'out':
                    $query->where('quantity', 0);
                    break;
                case 'expired':
                    $query->where('expiry_date', '<', now());
                    break;
                case 'expiring':
                    $query->whereBetween('expiry_date', [now(), now()->addDays(30)]);
                    break;
            }
        }

        $items = $query->latest()->paginate(15);
        $categories = InventoryItem::distinct()->pluck('category')->filter();

        return view('inventory.index', compact('items', 'categories'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date|after:today',
            'unit_cost' => 'required|numeric|min:0',
            'uom' => 'required|string|max:50',
        ]);

        $item = InventoryItem::create($data);

        // Create initial stock transaction if quantity > 0
        if ($data['quantity'] > 0) {
            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'type' => 'in',
                'quantity' => $data['quantity'],
                'reference' => 'Initial Stock',
                'remarks' => 'Initial inventory setup'
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully');
    }

    public function show(InventoryItem $inventoryItem)
    {
        $inventoryItem->load(['transactions' => function($q) {
            $q->latest()->limit(20);
        }]);
        
        return view('inventory.show', compact('inventoryItem'));
    }

    public function edit(InventoryItem $inventoryItem)
    {
        return view('inventory.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'reorder_level' => 'required|integer|min:0',
            'expiry_date' => 'nullable|date',
            'unit_cost' => 'required|numeric|min:0',
            'uom' => 'required|string|max:50',
        ]);

        $inventoryItem->update($data);

        return redirect()->route('inventory.show', $inventoryItem)
                        ->with('success', 'Inventory item updated successfully');
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();
        return redirect()->route('inventory.index')
                        ->with('success', 'Inventory item deleted successfully');
    }

    public function stockMovement(Request $request, InventoryItem $inventoryItem)
    {
        $data = $request->validate([
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
            'remarks' => 'nullable|string'
        ]);

        // For stock out, ensure we don't go negative
        if ($data['type'] === 'out' && $inventoryItem->quantity < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock available']);
        }

        // Create transaction
        InventoryTransaction::create([
            'inventory_item_id' => $inventoryItem->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'reference' => $data['reference'],
            'remarks' => $data['remarks']
        ]);

        // Update inventory quantity
        switch ($data['type']) {
            case 'in':
                $inventoryItem->increment('quantity', $data['quantity']);
                break;
            case 'out':
                $inventoryItem->decrement('quantity', $data['quantity']);
                break;
            case 'adjustment':
                $inventoryItem->update(['quantity' => $data['quantity']]);
                break;
        }

        return redirect()->route('inventory.show', $inventoryItem)
                        ->with('success', 'Stock movement recorded successfully');
    }

    public function lowStock()
    {
        $items = InventoryItem::whereRaw('quantity <= reorder_level')
                             ->orderBy('quantity')
                             ->paginate(15);
        
        return view('inventory.low-stock', compact('items'));
    }

    public function expiring()
    {
        $items = InventoryItem::whereNotNull('expiry_date')
                             ->where('expiry_date', '<=', now()->addDays(30))
                             ->orderBy('expiry_date')
                             ->paginate(15);
        
        return view('inventory.expiring', compact('items'));
    }
}
