@extends('layouts.app')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Add New Inventory Item</h1>
            <p class="text-gray-600 dark:text-gray-400">Create a new inventory item for hospital supplies</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Inventory
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <form action="{{ route('inventory.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Item Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="Enter item name">
                @error('name')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <input type="text" id="category" name="category" value="{{ old('category') }}" 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="e.g., Medications, Supplies, Equipment">
                @error('category')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Initial Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Initial Quantity *</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0" required 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="0">
                @error('quantity')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Unit of Measurement -->
            <div>
                <label for="uom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit of Measurement *</label>
                <select id="uom" name="uom" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Select unit</option>
                    <option value="unit" {{ old('uom') == 'unit' ? 'selected' : '' }}>Unit</option>
                    <option value="box" {{ old('uom') == 'box' ? 'selected' : '' }}>Box</option>
                    <option value="bottle" {{ old('uom') == 'bottle' ? 'selected' : '' }}>Bottle</option>
                    <option value="vial" {{ old('uom') == 'vial' ? 'selected' : '' }}>Vial</option>
                    <option value="tablet" {{ old('uom') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="capsule" {{ old('uom') == 'capsule' ? 'selected' : '' }}>Capsule</option>
                    <option value="ml" {{ old('uom') == 'ml' ? 'selected' : '' }}>ML</option>
                    <option value="liter" {{ old('uom') == 'liter' ? 'selected' : '' }}>Liter</option>
                    <option value="kg" {{ old('uom') == 'kg' ? 'selected' : '' }}>KG</option>
                    <option value="gram" {{ old('uom') == 'gram' ? 'selected' : '' }}>Gram</option>
                    <option value="pack" {{ old('uom') == 'pack' ? 'selected' : '' }}>Pack</option>
                </select>
                @error('uom')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Reorder Level -->
            <div>
                <label for="reorder_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reorder Level *</label>
                <input type="number" id="reorder_level" name="reorder_level" value="{{ old('reorder_level', 10) }}" min="0" required 
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
                <input type="number" id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" min="0" step="0.01" required 
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                       placeholder="0.00">
                @error('unit_cost')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expiry Date -->
            <div class="md:col-span-2">
                <label for="expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}" 
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
            <a href="{{ route('inventory.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                Create Item
            </button>
        </div>
    </form>
</div>

<!-- Auto-generate SKU info -->
<div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">SKU Auto-Generation</h3>
            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                <p>A unique SKU (Stock Keeping Unit) will be automatically generated for this item in the format: SKU-YYYYMMDD-XXXXXX</p>
            </div>
        </div>
    </div>
</div>
@endsection