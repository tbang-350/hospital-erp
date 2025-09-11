<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = InventoryTransaction::with('inventoryItem');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('remarks', 'like', "%{$search}%")
                  ->orWhereHas('inventoryItem', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by transaction type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if ($sortField === 'inventory_item') {
            $query->join('inventory_items', 'inventory_transactions.inventory_item_id', '=', 'inventory_items.id')
                  ->orderBy('inventory_items.name', $sortDirection)
                  ->select('inventory_transactions.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        // Per page selection
        $perPage = $request->get('per_page', 20);
        $transactions = $query->paginate($perPage);

        return view('inventory.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(InventoryItem $inventoryItem)
    {
        return view('inventory.transactions.create', compact('inventoryItem'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, InventoryItem $inventoryItem)
    {
        $request->validate([
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ]);

        // Additional validation for stock out
        if ($request->type === 'out' && $request->quantity > $inventoryItem->quantity) {
            return back()->withErrors([
                'quantity' => 'Cannot remove more stock than available. Current stock: ' . $inventoryItem->quantity
            ])->withInput();
        }

        DB::transaction(function () use ($request, $inventoryItem) {
            // Create the transaction record
            InventoryTransaction::create([
                'inventory_item_id' => $inventoryItem->id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'reference' => $request->reference,
                'remarks' => $request->remarks,
            ]);

            // Update inventory item quantity
            $newQuantity = $inventoryItem->quantity;
            
            switch ($request->type) {
                case 'in':
                    $newQuantity += $request->quantity;
                    break;
                case 'out':
                    $newQuantity -= $request->quantity;
                    break;
                case 'adjustment':
                    // For adjustments, the quantity field represents the new total quantity
                    $newQuantity = $request->quantity;
                    break;
            }

            $inventoryItem->update(['quantity' => max(0, $newQuantity)]);
        });

        return redirect()->route('inventory.show', $inventoryItem->id)
            ->with('success', 'Stock transaction recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->load('inventoryItem');
        return view('inventory.transactions.show', compact('inventoryTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryTransaction $inventoryTransaction)
    {
        // Generally, inventory transactions should not be editable for audit purposes
        // But if needed, implement with proper authorization and audit trail
        abort(403, 'Inventory transactions cannot be modified for audit purposes.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryTransaction $inventoryTransaction)
    {
        // Generally, inventory transactions should not be editable for audit purposes
        abort(403, 'Inventory transactions cannot be modified for audit purposes.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        // Generally, inventory transactions should not be deletable for audit purposes
        // But if needed for corrections, implement with proper authorization
        abort(403, 'Inventory transactions cannot be deleted for audit purposes.');
    }

    /**
     * Get transaction history for a specific inventory item
     */
    public function getItemTransactions(InventoryItem $inventoryItem)
    {
        $transactions = $inventoryItem->transactions()
            ->latest()
            ->paginate(10);

        return response()->json($transactions);
    }

    /**
     * Generate stock movement report
     */
    public function stockMovementReport(Request $request)
    {
        $query = InventoryTransaction::with('inventoryItem');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by transaction type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by inventory item
        if ($request->filled('inventory_item_id')) {
            $query->where('inventory_item_id', $request->inventory_item_id);
        }

        $transactions = $query->latest()->paginate(50);

        return view('inventory.reports.stock-movement', compact('transactions'));
    }

    /**
     * Export stock movement data
     */
    public function exportStockMovement(Request $request)
    {
        // Implementation for exporting stock movement data to CSV/Excel
        // This would typically use a package like Laravel Excel
        
        $query = InventoryTransaction::with('inventoryItem');

        // Apply same filters as report
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('inventory_item_id')) {
            $query->where('inventory_item_id', $request->inventory_item_id);
        }

        $transactions = $query->latest()->get();

        // For now, return JSON. In production, implement CSV/Excel export
        return response()->json([
            'message' => 'Export functionality would be implemented here',
            'count' => $transactions->count(),
            'data' => $transactions->take(10) // Sample data
        ]);
    }
}