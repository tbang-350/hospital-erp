@extends('layouts.app')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Inventory Item</h1>
            <p class="text-gray-600 dark:text-gray-400">Update inventory item details</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.show', $inventoryItem) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Item
            </a>
            <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Inventory
            </a>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <form action="{{ route('inventory.update', $inventoryItem) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- SKU (Read-only) -->
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SKU</label>
                <input type="text" id="sku" value="{{ $inventoryItem->sku }}" readonly 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400" 
                       placeholder="Auto-generated">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">SKU cannot be modified</p>
            </div>

            <!-- Item Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $inventoryItem->name) }}" required 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="Enter item name">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category', $inventoryItem->category) }}" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="e.g., Medications, Supplies, Equipment">
                @error('category')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Quantity (Read-only) -->
            <div>
                <label for="current_quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Quantity</label>
                <input type="number" id="current_quantity" value="{{ $inventoryItem->quantity }}" readonly 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use stock movement to adjust quantity</p>
            </div>

            <!-- Unit of Measurement -->
            <div>
                <label for="uom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit of Measurement *</label>
                <select id="uom" name="uom" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Select unit</option>
                    <option value="unit" {{ old('uom', $inventoryItem->uom) == 'unit' ? 'selected' : '' }}>Unit</option>
                    <option value="box" {{ old('uom', $inventoryItem->uom) == 'box' ? 'selected' : '' }}>Box</option>
                    <option value="bottle" {{ old('uom', $inventoryItem->uom) == 'bottle' ? 'selected' : '' }}>Bottle</option>
                    <option value="vial" {{ old('uom', $inventoryItem->uom) == 'vial' ? 'selected' : '' }}>Vial</option>
                    <option value="tablet" {{ old('uom', $inventoryItem->uom) == 'tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="capsule" {{ old('uom', $inventoryItem->uom) == 'capsule' ? 'selected' : '' }}>Capsule</option>
                    <option value="ml" {{ old('uom', $inventoryItem->uom) == 'ml' ? 'selected' : '' }}>ML</option>
                    <option value="liter" {{ old('uom', $inventoryItem->uom) == 'liter' ? 'selected' : '' }}>Liter</option>
                    <option value="kg" {{ old('uom', $inventoryItem->uom) == 'kg' ? 'selected' : '' }}>KG</option>
                    <option value="gram" {{ old('uom', $inventoryItem->uom) == 'gram' ? 'selected' : '' }}>Gram</option>
                    <option value="pack" {{ old('uom', $inventoryItem->uom) == 'pack' ? 'selected' : '' }}>Pack</option>
                </select>
                @error('uom')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Reorder Level -->
            <div>
                <label for="reorder_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reorder Level *</label>
                <input type="number" id="reorder_level" name="reorder_level" value="{{ old('reorder_level', $inventoryItem->reorder_level) }}" min="0" required 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="10">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Alert when stock falls below this level</p>
                @error('reorder_level')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Unit Cost -->
            <div>
                <label for="unit_cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit Cost (TZS) *</label>
                <input type="number" id="unit_cost" name="unit_cost" value="{{ old('unit_cost', $inventoryItem->unit_cost) }}" min="0" step="0.01" required 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="0.00">
                @error('unit_cost')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Supplier -->
            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier</label>
                <select id="supplier_id" name="supplier_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Select Supplier (Optional)</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id', $inventoryItem->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                            @if($supplier->contact_person)
                                - {{ $supplier->contact_person }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    <a href="{{ route('suppliers.create') }}" class="text-primary-600 hover:text-primary-700" target="_blank">
                        Add new supplier
                    </a>
                </p>
            </div>

            <!-- Expiry Date -->
            <div class="md:col-span-2">
                <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date', $inventoryItem->expiry_date?->format('Y-m-d')) }}" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank if item doesn't expire</p>
                @error('expiry_date')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('inventory.show', $inventoryItem) }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                Update Item
            </button>
        </div>
    </form>
</div>

<!-- Stock Movement Link -->
<div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
    <div class="flex items-center justify-between">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Stock Quantity Management</h3>
                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                    <p>To adjust stock quantities, use the stock movement feature to maintain proper inventory tracking.</p>
                </div>
            </div>
        </div>
        <div class="ml-4">
            <a href="{{ route('inventory.stock-movement', $inventoryItem) }}" class="inline-flex items-center px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                Manage Stock
            </a>
        </div>
    </div>
</div>
@endsection