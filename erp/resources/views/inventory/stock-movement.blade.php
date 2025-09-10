@extends('layouts.app')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Stock Movement</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $item->name }} ({{ $item->sku }})</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.show', $item) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Stock Movement Form -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Record Stock Movement</h2>
            </div>
            <form action="{{ route('inventory.stock-movement.store', $item) }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Transaction Type -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Transaction Type *</label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none">
                                <input type="radio" name="type" value="in" class="sr-only" {{ old('type') == 'in' ? 'checked' : '' }} required>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">Stock In</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">Add inventory</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-green-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none">
                                <input type="radio" name="type" value="out" class="sr-only" {{ old('type') == 'out' ? 'checked' : '' }} required>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">Stock Out</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">Remove inventory</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-red-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>
                            <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 p-4 shadow-sm focus:outline-none">
                                <input type="radio" name="type" value="adjustment" class="sr-only" {{ old('type') == 'adjustment' ? 'checked' : '' }} required>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-medium text-gray-900 dark:text-gray-100">Adjustment</span>
                                        <span class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">Correct inventory</span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-blue-600 hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity *</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                               placeholder="Enter quantity">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Current stock: {{ number_format($item->quantity) }} {{ $item->uom }}</p>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reference -->
                    <div>
                        <label for="reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reference</label>
                        <input type="text" id="reference" name="reference" value="{{ old('reference') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                               placeholder="e.g., PO-2024-001, Invoice #123">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Purchase order, invoice, or other reference</p>
                        @error('reference')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remarks -->
                    <div class="md:col-span-2">
                        <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks</label>
                        <textarea id="remarks" name="remarks" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white" 
                                  placeholder="Additional notes or comments">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('inventory.show', $item) }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Record Movement
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Item Summary -->
    <div class="space-y-6">
        <!-- Current Stock -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Current Stock</h2>
            </div>
            <div class="p-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($item->quantity) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide">{{ $item->uom }}</div>
                    <div class="mt-4">
                        @if($item->isLowStock())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Low Stock
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                In Stock
                            </span>
                        @endif
                    </div>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Reorder Level</span>
                        <span class="text-gray-900 dark:text-gray-100">{{ number_format($item->reorder_level) }} {{ $item->uom }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Unit Cost</span>
                        <span class="text-gray-900 dark:text-gray-100">TZS {{ number_format($item->unit_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-semibold">
                        <span class="text-gray-600 dark:text-gray-400">Total Value</span>
                        <span class="text-gray-900 dark:text-gray-100">TZS {{ number_format($item->quantity * $item->unit_cost, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Summary -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Transaction Summary</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Stock In</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($item->transactions->stockIn()->sum('quantity')) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Stock Out</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($item->transactions->stockOut()->sum('quantity')) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Adjustments</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $item->transactions->adjustment()->count() }}</span>
                </div>
                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transactions</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $item->transactions->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Transaction History</h2>
    </div>
    @if($item->transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($item->transactions->sortByDesc('created_at') as $transaction)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $transaction->created_at->format('M d, Y') }}<br>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('g:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->type === 'in')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Stock In
                                    </span>
                                @elseif($transaction->type === 'out')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Stock Out
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                        </svg>
                                        Adjustment
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <span class="font-medium">{{ number_format($transaction->quantity) }}</span> {{ $item->uom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $transaction->reference ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                {{ $transaction->remarks ?: '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No transactions yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Record your first stock movement using the form above.</p>
        </div>
    @endif
</div>

<script>
// Handle radio button selection styling
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="type"]');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove selected styling from all labels
            radioButtons.forEach(r => {
                const label = r.closest('label');
                const icon = label.querySelector('svg');
                label.classList.remove('border-primary-500', 'ring-2', 'ring-primary-500');
                icon.classList.add('hidden');
            });
            
            // Add selected styling to checked radio
            if (this.checked) {
                const label = this.closest('label');
                const icon = label.querySelector('svg');
                label.classList.add('border-primary-500', 'ring-2', 'ring-primary-500');
                icon.classList.remove('hidden');
            }
        });
        
        // Check if already selected on page load
        if (radio.checked) {
            const label = radio.closest('label');
            const icon = label.querySelector('svg');
            label.classList.add('border-primary-500', 'ring-2', 'ring-primary-500');
            icon.classList.remove('hidden');
        }
    });
});
</script>
@endsection