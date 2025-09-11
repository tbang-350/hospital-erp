@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                    Suppliers Management
                </h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Manage your suppliers and vendor relationships</p>
            </div>

            <!-- Actions Section -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <button onclick="openAddSupplierModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Supplier
                </button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <form method="GET" action="{{ route('suppliers.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Suppliers</label>
                        <div class="relative">
                            <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name, contact person, or email..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="status" name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Per Page</label>
                        <select id="per_page" name="per_page" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25 per page</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50 per page</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Suppliers Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('suppliers.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-green-600 dark:hover:text-green-400">
                                    Supplier Details
                                    @if(request('sort') === 'name')
                                        @if(request('direction') === 'asc')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact Info</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('suppliers.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-green-600 dark:hover:text-green-400">
                                    Status
                                    @if(request('sort') === 'status')
                                        @if(request('direction') === 'asc')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('suppliers.index', array_merge(request()->query(), ['sort' => 'inventory_items_count', 'direction' => request('sort') === 'inventory_items_count' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center hover:text-green-600 dark:hover:text-green-400">
                                    Items Count
                                    @if(request('sort') === 'inventory_items_count')
                                        @if(request('direction') === 'asc')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </a>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $supplier->name }}</div>
                                        @if($supplier->tax_id)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Tax ID: {{ $supplier->tax_id }}</div>
                                        @endif
                                        @if($supplier->contact_person)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Contact: {{ $supplier->contact_person }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if($supplier->email)
                                            <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                <a href="mailto:{{ $supplier->email }}" class="hover:text-green-600 dark:hover:text-green-400">{{ $supplier->email }}</a>
                                            </div>
                                        @endif
                                        @if($supplier->phone)
                                            <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <a href="tel:{{ $supplier->phone }}" class="hover:text-green-600 dark:hover:text-green-400">{{ $supplier->phone }}</a>
                                            </div>
                                        @endif
                                        @if(!$supplier->email && !$supplier->phone)
                                            <span class="text-gray-400 dark:text-gray-500">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($supplier->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                        {{ $supplier->inventory_items_count }} items
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="inline-flex items-center px-3 py-1.5 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <button onclick="openEditSupplierModal({{ $supplier->id }}, '{{ addslashes($supplier->name) }}', '{{ addslashes($supplier->contact_person ?? '') }}', '{{ addslashes($supplier->email ?? '') }}', '{{ addslashes($supplier->phone ?? '') }}', '{{ $supplier->status }}', '{{ addslashes($supplier->tax_id ?? '') }}', '{{ addslashes($supplier->address ?? '') }}', '{{ addslashes($supplier->notes ?? '') }}')" class="inline-flex items-center px-3 py-1.5 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        @if($supplier->inventory_items_count == 0)
                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this supplier?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <button disabled title="Cannot delete - has inventory items" class="inline-flex items-center px-3 py-1.5 text-gray-400 dark:text-gray-500 font-medium rounded-lg cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <div class="text-center">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No suppliers found</h3>
                                            <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by adding your first supplier.</p>
                                            <button onclick="openAddSupplierModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Add First Supplier
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($suppliers->hasPages())
            <div class="flex justify-center mt-6">
                {{ $suppliers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

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
            <form id="addSupplierForm" action="{{ route('suppliers.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="add_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier Name *</label>
                        <input type="text" id="add_name" name="name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="add_contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Person</label>
                        <input type="text" id="add_contact_person" name="contact_person" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="add_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" id="add_email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="add_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <input type="text" id="add_phone" name="phone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="add_tax_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax ID</label>
                        <input type="text" id="add_tax_id" name="tax_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="add_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="add_status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="add_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <textarea id="add_address" name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"></textarea>
                </div>
                <div>
                    <label for="add_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea id="add_notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
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

<!-- Edit Supplier Modal -->
<div id="editSupplierModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-xl bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Supplier</h3>
                <button onclick="closeEditSupplierModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="editSupplierForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier Name *</label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="edit_contact_person" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Person</label>
                        <input type="text" id="edit_contact_person" name="contact_person" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" id="edit_email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="edit_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                        <input type="text" id="edit_phone" name="phone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="edit_tax_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tax ID</label>
                        <input type="text" id="edit_tax_id" name="tax_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label for="edit_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="edit_status" name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="edit_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                    <textarea id="edit_address" name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"></textarea>
                </div>
                <div>
                    <label for="edit_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea id="edit_notes" name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-gray-100"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeEditSupplierModal()" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-lg transition-all duration-200">
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Debounced search functionality
let searchTimeout;
const searchInput = document.getElementById('search');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
}
</script>
@endpush

@section('scripts')
<script>
    // Auto-submit search form on status change
    document.getElementById('status').addEventListener('change', function() {
        this.form.submit();
    });

    // Modal functions
    function openAddSupplierModal() {
        document.getElementById('addSupplierModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddSupplierModal() {
        document.getElementById('addSupplierModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('addSupplierForm').reset();
    }

    function openEditSupplierModal(id, name, contactPerson, email, phone, status, taxId, address, notes) {
        document.getElementById('editSupplierForm').action = `/suppliers/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_contact_person').value = contactPerson;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_tax_id').value = taxId;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_notes').value = notes;
        document.getElementById('editSupplierModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditSupplierModal() {
        document.getElementById('editSupplierModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modals when clicking outside
    document.getElementById('addSupplierModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddSupplierModal();
        }
    });

    document.getElementById('editSupplierModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditSupplierModal();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddSupplierModal();
            closeEditSupplierModal();
        }
    });

    // Handle supplier form submission
    document.getElementById('addSupplierForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
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
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeAddSupplierModal();
                
                // Show success message and reload page to show new supplier
                alert('Supplier added successfully!');
                window.location.reload();
            } else {
                let errorMessage = 'Error adding supplier: ' + (data.message || 'Unknown error');
                if (data.errors) {
                    const errorList = Object.values(data.errors).flat();
                    errorMessage += '\n\nValidation errors:\n' + errorList.join('\n');
                }
                alert(errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = 'Error adding supplier. Please try again.';
            if (error.message) {
                errorMessage = 'Error: ' + error.message;
            } else if (error.errors) {
                const errorList = Object.values(error.errors).flat();
                errorMessage = 'Validation errors:\n' + errorList.join('\n');
            }
            alert(errorMessage);
        });
    });
</script>
@endsection