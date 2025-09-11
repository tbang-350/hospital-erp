@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-8 gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('inventory.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Inventory
                </a>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        {{ $inventoryItem->name }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">SKU: {{ $inventoryItem->sku }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.stock-movement', $inventoryItem) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                    </svg>
                    Stock Movement
                </a>
                <a href="{{ route('inventory.edit', $inventoryItem) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Item
                </a>
            </div>
        </div>

        <!-- Status Alerts -->
        @if($inventoryItem->isLowStock())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6 shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-red-800 dark:text-red-200">Low Stock Alert</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>Current stock ({{ $inventoryItem->quantity }}) is below the reorder level ({{ $inventoryItem->reorder_level }}). Consider restocking soon.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($inventoryItem->isExpiringSoon())
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-200">Expiring Soon</h3>
                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <p>This item expires on {{ $inventoryItem->expiry_date->format('M d, Y') }}. Use or dispose of before expiration.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($inventoryItem->isExpired())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-6 shadow-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-red-800 dark:text-red-200">Expired Item</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>This item expired on {{ $inventoryItem->expiry_date->format('M d, Y') }}. Do not use and dispose of properly.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Item Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Item Details
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">SKU</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded">{{ $inventoryItem->sku }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventoryItem->category ?: 'Uncategorized' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Stock</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    <span class="font-semibold">{{ number_format($inventoryItem->quantity) }}</span> {{ $inventoryItem->uom }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reorder Level</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($inventoryItem->reorder_level) }} {{ $inventoryItem->uom }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Cost</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">TZS {{ number_format($inventoryItem->unit_cost, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Value</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">TZS {{ number_format($inventoryItem->quantity * $inventoryItem->unit_cost, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Expiry Date</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($inventoryItem->expiry_date)
                                        {{ $inventoryItem->expiry_date->format('M d, Y') }}
                                        @if($inventoryItem->isExpired())
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Expired
                                            </span>
                                        @elseif($inventoryItem->isExpiringSoon())
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                Expiring Soon
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No expiry date</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                    @if($inventoryItem->supplier)
                                        <a href="{{ route('suppliers.show', $inventoryItem->supplier) }}" class="text-primary-600 hover:text-primary-700 hover:underline">
                                            {{ $inventoryItem->supplier->name }}
                                        </a>
                                        @if($inventoryItem->supplier->contact_person)
                                            <span class="text-gray-500 dark:text-gray-400"> - {{ $inventoryItem->supplier->contact_person }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">No supplier specified</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Status</dt>
                                <dd class="mt-1">
                                    @if($inventoryItem->isLowStock())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            In Stock
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventoryItem->created_at->format('M d, Y \a\t g:i A') }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $inventoryItem->updated_at->format('M d, Y \a\t g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Quick Stats Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Quick Stats
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Transactions</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $inventoryItem->transactions->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Stock In</span>
                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $inventoryItem->transactions->where('type', 'in')->sum('quantity') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Stock Out</span>
                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $inventoryItem->transactions->where('type', 'out')->sum('quantity') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Adjustments</span>
                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $inventoryItem->transactions->where('type', 'adjustment')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Quick Stats
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Transactions</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $inventoryItem->transactions->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Stock In</span>
                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">{{ $inventoryItem->transactions->where('type', 'in')->sum('quantity') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Stock Out</span>
                            <span class="text-sm font-semibold text-red-600 dark:text-red-400">{{ $inventoryItem->transactions->where('type', 'out')->sum('quantity') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Adjustments</span>
                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $inventoryItem->transactions->where('type', 'adjustment')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
