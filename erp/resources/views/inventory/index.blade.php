@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                <div>
                    <h1
                        class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        Inventory Management
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-2">Manage hospital inventory, stock levels, and supplies
                    </p>
                </div>

                <!-- Actions Section -->
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <a href="{{ route('inventory.low-stock') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        Low Stock Alert
                    </a>
                    <a href="{{ route('inventory.expiring') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Expiring Items
                    </a>
                    <a href="{{ route('inventory.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Item
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Items</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($totalItems) }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Low Stock Items</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($lowStockCount) }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Expiring Soon</p>
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($expiringSoonCount) }}</p>
                        </div>
                        <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Value</p>
                            @php
                                $formattedValue = $totalInventoryValue >= 1000000 
                                    ? number_format($totalInventoryValue / 1000000, 1) . 'M'
                                    : ($totalInventoryValue >= 1000 
                                        ? number_format($totalInventoryValue / 1000, 1) . 'K'
                                        : number_format($totalInventoryValue, 2));
                            @endphp
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400" title="TZS {{ number_format($totalInventoryValue, 2) }}">
                                TZS {{ $formattedValue }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">All inventory items</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-col sm:flex-row gap-3 w-full mb-6">
                <div class="relative flex-1">
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Search inventory items..."
                        class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white w-full">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <select id="category_filter" name="category" onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}</option>
                    @endforeach
                </select>

                <select id="stock_status_filter" name="stock_status" onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="expired" {{ request('stock_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="expiring" {{ request('stock_status') == 'expiring' ? 'selected' : '' }}>Expiring Soon
                    </option>
                </select>

                <select id="per_page" name="per_page" onchange="this.form.submit()"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per page</option>
                    <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50 per page</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>

            <!-- Inventory Items Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-purple-100 dark:hover:bg-gray-600 transition-colors" onclick="sortTable('name')">
                                    <div class="flex items-center space-x-1">
                                        <span>Item Details</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-purple-100 dark:hover:bg-gray-600 transition-colors" onclick="sortTable('category')">
                                    <div class="flex items-center space-x-1">
                                        <span>Category</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-purple-100 dark:hover:bg-gray-600 transition-colors" onclick="sortTable('quantity')">
                                    <div class="flex items-center space-x-1">
                                        <span>Stock</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-purple-100 dark:hover:bg-gray-600 transition-colors" onclick="sortTable('unit_cost')">
                                    <div class="flex items-center space-x-1">
                                        <span>Unit Cost</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-purple-100 dark:hover:bg-gray-600 transition-colors" onclick="sortTable('supplier')">
                                    <div class="flex items-center space-x-1">
                                        <span>Supplier</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-purple-100 dark:hover:bg-gray-600 transition-colors" onclick="sortTable('expiry_date')">
                                    <div class="flex items-center space-x-1">
                                        <span>Expiry</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $item->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $item->sku }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($item->category)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200">
                                                {{ $item->category }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            <span class="font-medium">{{ number_format($item->quantity) }}</span>
                                            {{ $item->uom }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Reorder at: {{ number_format($item->reorder_level) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        TZS {{ number_format($item->unit_cost, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if($item->supplier)
                                            <a href="{{ route('suppliers.show', $item->supplier) }}" class="text-primary-600 hover:text-primary-700 hover:underline">
                                                {{ $item->supplier->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if ($item->expiry_date)
                                            {{ $item->expiry_date->format('M d, Y') }}
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            @if ($item->quantity == 0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                    Out of Stock
                                                </span>
                                            @elseif($item->isLowStock())
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                                    Low Stock
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                    In Stock
                                                </span>
                                            @endif

                                            @if ($item->isExpired())
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                    Expired
                                                </span>
                                            @elseif($item->isExpiringSoon())
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200">
                                                    Expiring Soon
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('inventory.show', $item) }}"
                                                class="inline-flex items-center px-3 py-1.5 text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 font-medium rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                View
                                            </a>
                                            <a href="{{ route('inventory.edit', $item) }}"
                                class="inline-flex items-center px-3 py-1.5 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                Edit
                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3">
                                                </path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No
                                                inventory items found</h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding
                                                your first inventory item.</p>
                                            <div class="mt-6">
                                                <button onclick="openAddItemModal()"
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Add New Item
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($items->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Include the Add Item Modal Partial -->
    <!-- Add Supplier Modal -->
    <div id="addSupplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 60;">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Add New Supplier</h3>
                    <button onclick="closeAddSupplierModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="addSupplierForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="supplier_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier Name *</label>
                            <input type="text" id="supplier_name" name="name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="supplier_contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Person</label>
                            <input type="text" id="supplier_contact_person" name="contact_person" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="supplier_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" id="supplier_email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="supplier_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                            <input type="text" id="supplier_phone" name="phone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="supplier_tax_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax ID</label>
                            <input type="text" id="supplier_tax_id" name="tax_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="supplier_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select id="supplier_status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="supplier_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                        <textarea id="supplier_address" name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div>
                        <label for="supplier_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                        <textarea id="supplier_notes" name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="flex items-center justify-end pt-4 border-t border-gray-200 dark:border-gray-700 gap-3">
                        <button type="button" onclick="closeAddSupplierModal()" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-lg transition-all duration-200">
                            Add Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        // Sorting functionality
         function sortTable(column) {
             const url = new URL(window.location);
             const currentSort = url.searchParams.get('sort');
             const currentDirection = url.searchParams.get('direction');
             
             let newDirection = 'asc';
             if (currentSort === column && currentDirection === 'asc') {
                 newDirection = 'desc';
             }
             
             url.searchParams.set('sort', column);
             url.searchParams.set('direction', newDirection);
             
             window.location.href = url.toString();
         }

         // Handle search input with debounce
         document.getElementById('search').addEventListener('input', function(e) {
             clearTimeout(this.searchTimeout);
             this.searchTimeout = setTimeout(() => {
                 this.form.submit();
             }, 500);
         });

         // Handle per page selection
         document.getElementById('per_page').addEventListener('change', function(e) {
             this.form.submit();
         });

        // Supplier Modal Functions
        function openAddSupplierModal() {
            document.getElementById('addSupplierModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddSupplierModal() {
            document.getElementById('addSupplierModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('addSupplierForm').reset();
        }

        // Handle supplier form submission
        document.getElementById('addSupplierForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Adding...';
            
            fetch('/suppliers', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Add new supplier to both dropdowns
                    const addOption = new Option(data.supplier.name, data.supplier.id);
                    const editOption = new Option(data.supplier.name, data.supplier.id);
                    
                    const modalSupplierSelect = document.getElementById('modal_supplier_id');
                    const editSupplierSelect = document.getElementById('edit_supplier_id');
                    
                    if (modalSupplierSelect) {
                        modalSupplierSelect.add(addOption);
                        modalSupplierSelect.value = data.supplier.id;
                    }
                    
                    if (editSupplierSelect) {
                        editSupplierSelect.add(editOption);
                        editSupplierSelect.value = data.supplier.id;
                    }
                    
                    closeAddSupplierModal();
                    
                    // Show success message
                    alert('Supplier added successfully!');
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Validation errors:\n';
                        Object.keys(data.errors).forEach(field => {
                            errorMessage += `${field}: ${data.errors[field].join(', ')}\n`;
                        });
                        alert(errorMessage);
                    } else {
                        alert('Error adding supplier: ' + (data.message || 'Unknown error'));
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding supplier: ' + error.message + '. Please check the console for more details.');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = 'Add Supplier';
            });
        });
    </script>
@endsection
