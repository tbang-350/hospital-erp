@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('payrolls.show', $payroll) }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Payroll
                </a>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Edit Payroll
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Update payroll record for {{ $payroll->employee->full_name }}</p>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="max-w-4xl mx-auto">
            <form action="{{ route('payrolls.update', $payroll) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Employee Information (Read-only) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Employee Information</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Employee details (read-only)</p>
                    </div>
                    <div class="p-6">
                        <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <span class="text-blue-700 dark:text-blue-300 font-medium">Name:</span>
                                    <span class="text-blue-900 dark:text-blue-100 ml-1">{{ $payroll->employee->full_name }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700 dark:text-blue-300 font-medium">Position:</span>
                                    <span class="text-blue-900 dark:text-blue-100 ml-1">{{ $payroll->employee->position ?: 'No position' }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700 dark:text-blue-300 font-medium">Base Salary:</span>
                                    <span class="text-blue-900 dark:text-blue-100 ml-1">${{ number_format($payroll->employee->salary, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="employee_id" value="{{ $payroll->employee_id }}">
                    </div>
                </div>

                <!-- Payroll Details -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Payroll Details</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Period and salary information</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Period -->
                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pay Period <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="period" name="period" value="{{ old('period', $payroll->period) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('period') border-red-500 @enderror"
                                       placeholder="e.g., 2024-01 or January 2024">
                                @error('period')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Format: YYYY-MM or descriptive period</p>
                            </div>

                            <!-- Basic Salary -->
                            <div>
                                <label for="basic_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Basic Salary <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" id="basic_salary" name="basic_salary" value="{{ old('basic_salary', $payroll->basic_salary) }}" required step="0.01" min="0" onchange="calculateNetPay()"
                                           class="w-full pl-7 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('basic_salary') border-red-500 @enderror"
                                           placeholder="0.00">
                                </div>
                                @error('basic_salary')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Allowances -->
                            <div>
                                <label for="allowances" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Allowances
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" id="allowances" name="allowances" value="{{ old('allowances', $payroll->allowances) }}" step="0.01" min="0" onchange="calculateNetPay()"
                                           class="w-full pl-7 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('allowances') border-red-500 @enderror"
                                           placeholder="0.00">
                                </div>
                                @error('allowances')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Housing, transport, medical allowances, etc.</p>
                            </div>

                            <!-- Deductions -->
                            <div>
                                <label for="deductions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Deductions
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" id="deductions" name="deductions" value="{{ old('deductions', $payroll->deductions) }}" step="0.01" min="0" onchange="calculateNetPay()"
                                           class="w-full pl-7 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('deductions') border-red-500 @enderror"
                                           placeholder="0.00">
                                </div>
                                @error('deductions')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tax, insurance, loans, etc.</p>
                            </div>

                            <!-- Net Pay (Calculated) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Net Pay (Calculated)
                                </label>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="net-pay-display">
                                        ${{ number_format($payroll->net_pay, 2) }}
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Basic Salary + Allowances - Deductions</p>
                                </div>
                                <input type="hidden" id="net_pay" name="net_pay" value="{{ old('net_pay', $payroll->net_pay) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Payment Status</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Current payment status and date</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Payment Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Payment Status
                                </label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="mark_as_paid" value="0" {{ !$payroll->paid_at ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" onchange="togglePaidDate()">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pending Payment</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="mark_as_paid" value="1" {{ $payroll->paid_at ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600" onchange="togglePaidDate()">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mark as Paid</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Paid Date -->
                            <div id="paid-date-field" class="{{ !$payroll->paid_at ? 'hidden' : '' }}">
                                <label for="paid_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Payment Date
                                </label>
                                <input type="date" id="paid_at" name="paid_at" value="{{ old('paid_at', $payroll->paid_at ? $payroll->paid_at->format('Y-m-d') : date('Y-m-d')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('paid_at') border-red-500 @enderror">
                                @error('paid_at')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Additional Information</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Optional metadata and notes</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Meta Information -->
                            <div>
                                <label for="meta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Notes & Metadata
                                </label>
                                <textarea id="meta" name="meta" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white @error('meta') border-red-500 @enderror"
                                          placeholder="Additional notes, overtime hours, bonuses, etc. (JSON format or plain text)">{{ old('meta', is_array($payroll->meta) || is_object($payroll->meta) ? json_encode($payroll->meta, JSON_PRETTY_PRINT) : $payroll->meta) }}</textarea>
                                @error('meta')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Optional: Add any additional information about this payroll</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('payrolls.show', $payroll) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Payroll
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function calculateNetPay() {
    const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
    const allowances = parseFloat(document.getElementById('allowances').value) || 0;
    const deductions = parseFloat(document.getElementById('deductions').value) || 0;
    
    const netPay = basicSalary + allowances - deductions;
    
    document.getElementById('net-pay-display').textContent = '$' + netPay.toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('net_pay').value = netPay.toFixed(2);
}

function togglePaidDate() {
    const markAsPaidRadios = document.querySelectorAll('input[name="mark_as_paid"]');
    const paidDateField = document.getElementById('paid-date-field');
    const paidAtInput = document.getElementById('paid_at');
    
    const isPaid = Array.from(markAsPaidRadios).find(radio => radio.checked && radio.value === '1');
    
    if (isPaid) {
        paidDateField.classList.remove('hidden');
        if (!paidAtInput.value) {
            paidAtInput.value = new Date().toISOString().split('T')[0];
        }
    } else {
        paidDateField.classList.add('hidden');
        paidAtInput.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateNetPay();
    togglePaidDate();
});
</script>
@endsection