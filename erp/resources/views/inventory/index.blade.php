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
                    <button onclick="openAddItemModal()"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Item
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Items</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $items->total() }}</p>
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
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ $items->filter(fn($item) => $item->isLowStock())->count() }}</p>
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
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">
                                {{ $items->filter(fn($item) => $item->isExpiringSoon())->count() }}</p>
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
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">TZS
                                {{ number_format($items->sum(fn($item) => $item->quantity * $item->unit_cost), 2) }}</p>
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
            <div class="flex flex-col sm:flex-row gap-3 w-full mb-6">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search inventory items..."
                        class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white w-full">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <select
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}</option>
                    @endforeach
                </select>

                <select
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="expired" {{ request('stock_status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="expiring" {{ request('stock_status') == 'expiring' ? 'selected' : '' }}>Expiring Soon
                    </option>
                </select>
            </div>

            <!-- Inventory Items Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Item Details</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Stock</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Unit Cost</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Supplier</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Expiry</th>
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
                                            <button
                                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->category) }}', '{{ $item->uom }}', {{ $item->reorder_level }}, {{ $item->unit_cost }}, '{{ $item->expiry_date?->format('Y-m-d') }}', '{{ $item->sku }}', '{{ $item->supplier_id ?? '' }}')"
                                                class="inline-flex items-center px-3 py-1.5 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Edit
                                            </button>
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
    @include('inventory.partials.add-item-modal')

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

    <!-- Edit Item Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-purple-600 dark:text-purple-400">Edit Inventory Item</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="editForm" method="POST" class="mt-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SKU (Read-only) -->
                        <div>
                            <label for="edit_sku"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SKU</label>
                            <input type="text" id="edit_sku" readonly
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">SKU cannot be modified</p>
                        </div>

                        <!-- Item Name -->
                        <div>
                            <label for="edit_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item Name *</label>
                            <input type="text" id="edit_name" name="name" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="edit_category"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <input type="text" id="edit_category" name="category"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Unit of Measurement -->
                        <div>
                            <label for="edit_uom"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit of Measurement
                                *</label>
                            <select id="edit_uom" name="uom" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select unit</option>
                                <option value="unit">Unit</option>
                                <option value="box">Box</option>
                                <option value="bottle">Bottle</option>
                                <option value="vial">Vial</option>
                                <option value="tablet">Tablet</option>
                                <option value="capsule">Capsule</option>
                                <option value="ml">ML</option>
                                <option value="liter">Liter</option>
                                <option value="kg">KG</option>
                                <option value="gram">Gram</option>
                                <option value="pack">Pack</option>
                            </select>
                        </div>

                        <!-- Reorder Level -->
                        <div>
                            <label for="edit_reorder_level"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reorder Level
                                *</label>
                            <input type="number" id="edit_reorder_level" name="reorder_level" min="0" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Alert when stock falls below this
                                level</p>
                        </div>

                        <!-- Unit Cost -->
                        <div>
                            <label for="edit_unit_cost"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit Cost (TZS)
                                *</label>
                            <input type="number" id="edit_unit_cost" name="unit_cost" min="0" step="0.01"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="edit_expiry_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Expiry Date</label>
                            <input type="date" id="edit_expiry_date" name="expiry_date"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank if item doesn't expire</p>
                        </div>

                        <!-- Supplier -->
                        <div>
                            <label for="edit_supplier_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier</label>
                            <select id="edit_supplier_id" name="supplier_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Supplier (Optional)</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <button type="button" onclick="openAddSupplierModal()" class="text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                                    + Add new supplier
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div
                        class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700 mt-6 gap-3">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                            Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add Item Modal Functions (using existing partial functions)
        function openAddModal() {
            openAddItemModal();
        }

        function openAddItemModal() {
            document.getElementById('addItemModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddItemModal() {
            document.getElementById('addItemModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Reset form
            document.querySelector('#addItemModal form').reset();
        }

        // Edit Item Modal Functions
        function openEditModal(id, name, category, uom, reorderLevel, unitCost, expiryDate, sku, supplierId) {
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Set form action
            document.getElementById('editForm').action = `/inventory/${id}`;

            // Populate form fields
            document.getElementById('edit_sku').value = sku;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category').value = category || '';
            document.getElementById('edit_uom').value = uom;
            document.getElementById('edit_reorder_level').value = reorderLevel;
            document.getElementById('edit_unit_cost').value = unitCost;
            document.getElementById('edit_expiry_date').value = expiryDate || '';
            document.getElementById('edit_supplier_id').value = supplierId || '';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addItemModal');
            const editModal = document.getElementById('editModal');

            if (event.target === addModal) {
                closeAddItemModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }

        // Close modals with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddItemModal();
                closeEditModal();
            }
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
