<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asset::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tag', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by warranty status
        if ($request->filled('warranty_status')) {
            switch ($request->warranty_status) {
                case 'expiring':
                    $query->expiringWarranty();
                    break;
                case 'expired':
                    $query->whereNotNull('warranty_expiry')
                          ->where('warranty_expiry', '<', now());
                    break;
                case 'active':
                    $query->whereNotNull('warranty_expiry')
                          ->where('warranty_expiry', '>', now()->addDays(30));
                    break;
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $assets = $query->paginate(10)->withQueryString();

        // Get stats
        $stats = [
            'total' => Asset::count(),
            'categories' => Asset::distinct('category')->count('category'),
            'expiring_warranty' => Asset::expiringWarranty()->count(),
            'total_value' => Asset::sum('purchase_cost'),
        ];

        // Get filter options
        $categories = Asset::distinct('category')
            ->whereNotNull('category')
            ->pluck('category')
            ->sort();

        $locations = Asset::distinct('location')
            ->whereNotNull('location')
            ->pluck('location')
            ->sort();

        return view('assets.index', compact('assets', 'stats', 'categories', 'locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag' => 'required|string|unique:assets,tag',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'meta' => 'nullable|array',
        ]);

        $asset = Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        $asset->load('maintenanceOrders');

        // Get maintenance stats
        $maintenanceStats = [
            'total_orders' => $asset->maintenanceOrders()->count(),
            'pending_orders' => $asset->maintenanceOrders()->where('status', 'pending')->count(),
            'completed_orders' => $asset->maintenanceOrders()->where('status', 'completed')->count(),
            'total_cost' => $asset->maintenanceOrders()->sum('cost'),
        ];

        return view('assets.show', compact('asset', 'maintenanceStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'tag' => [
                'required',
                'string',
                Rule::unique('assets', 'tag')->ignore($asset->id)
            ],
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after_or_equal:purchase_date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'meta' => 'nullable|array',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        // Check if asset has maintenance orders
        if ($asset->maintenanceOrders()->exists()) {
            return back()->with('error', 'Cannot delete asset with existing maintenance orders.');
        }

        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully.');
    }
}
