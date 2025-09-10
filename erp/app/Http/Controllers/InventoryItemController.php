<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use App\Models\Supplier;
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
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('inventory.index', compact('items', 'categories', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        return view('inventory.create', compact('suppliers'));
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
            'supplier' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string|max:1000',
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

        // Pass as 'item' to match the view expectations
        return view('inventory.show', ['item' => $inventoryItem]);
    }

    public function edit(InventoryItem $inventoryItem)
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        return view('inventory.edit', compact('inventoryItem', 'suppliers'));
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
            'supplier' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string|max:1000',
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
        $lowStockItems = InventoryItem::whereRaw('quantity <= reorder_level')
                                ->orderBy('quantity')
                                ->get();

        $criticalItems = $lowStockItems->where('quantity', '<=', 5);
        $categoriesAffected = $lowStockItems->pluck('category')->filter()->unique()->count();
        $totalValueAtRisk = $lowStockItems->sum(function($item) {
            return $item->quantity * $item->unit_cost;
        });

        return view('inventory.low-stock', compact('lowStockItems', 'criticalItems', 'categoriesAffected', 'totalValueAtRisk'));
    }

    public function expiring()
    {
        $days = request('days', 30);

        $expiredItems = InventoryItem::whereNotNull('expiry_date')
                               ->where('expiry_date', '<', now())
                               ->orderBy('expiry_date')
                               ->get();

        $expiringSoonItems = InventoryItem::whereNotNull('expiry_date')
                                     ->where('expiry_date', '>=', now())
                                     ->where('expiry_date', '<=', now()->addDays($days))
                                     ->orderBy('expiry_date')
                                     ->get();

        $allExpiringItems = $expiredItems->merge($expiringSoonItems);

        return view('inventory.expiring', compact('expiredItems', 'expiringSoonItems', 'allExpiringItems'));
    }
}
