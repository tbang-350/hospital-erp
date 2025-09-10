@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-8 gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('patients.index') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Patients
                </a>
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">MRN: {{ $patient->mrn }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <button onclick="openAppointmentModal()"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Schedule Appointment
                </button>
                <button onclick="openInvoiceModal()"
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Create Invoice
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Recent Appointments Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Recent Appointments
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($patient->appointments->take(5) as $appointment)
                        <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @php
                                                $statusColors = [
                                                    'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
                                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200' }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $appointment->scheduled_at?->format('l, F j, Y \\a\\t g:i A') }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                @if($appointment->location)
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        </svg>
                                                        {{ $appointment->location }}
                                                    </span>
                                                @endif
                                                @if($appointment->reason)
                                                    <span class="ml-2">• {{ Str::limit($appointment->reason, 50) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('appointments.show', $appointment) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No appointments yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Schedule the first appointment for this patient.</p>
                            <button onclick="openAppointmentModal()"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Schedule Appointment
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Invoices Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-700 dark:to-gray-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Recent Invoices
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($patient->invoices->take(5) as $invoice)
                        <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @php
                                                $statusColors = [
                                                    'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
                                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
                                                    'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200' }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $invoice->invoice_number }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</span>
                                                @if($invoice->issued_date)
                                                    <span class="ml-2">• Issued {{ $invoice->issued_date->format('M d, Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('invoices.show', $invoice) }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No invoices yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Create the first invoice for this patient.</p>
                            <button onclick="openInvoiceModal()"
                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Invoice
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Demographics Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-700 dark:to-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Demographics
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->dob }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ ucfirst($patient->gender) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->phone }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->email }}</span>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Address:</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-900 dark:text-white">
                                {{ $patient->address }}<br>
                                {{ $patient->city }}, {{ $patient->state }} {{ $patient->zip_code }}
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Insurance #:</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $patient->insurance_number }}</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency Contact:</span>
                            </div>
                            <div class="text-sm text-gray-900 dark:text-white">
                                <div class="font-medium">{{ $patient->emergency_contact_name }}</div>
                                <div class="text-gray-500 dark:text-gray-400">{{ $patient->emergency_contact_phone }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Appointment Modal -->
<div id="appointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Schedule Appointment</h3>
            <button onclick="closeAppointmentModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('appointments.store') }}" method="POST" class="p-4 space-y-4">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input name="location" value="{{ old('location') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Enter location" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <textarea name="reason" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                              placeholder="Enter reason for appointment">{{ old('reason') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeAppointmentModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 transition-colors">
                    Schedule Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Create Invoice Modal -->
<div id="invoiceModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Create Invoice</h3>
            <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form action="{{ route('invoices.store') }}" method="POST" x-data="invoiceForm()" class="p-4 space-y-4">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}" />

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                <input name="currency" value="{{ old('currency','TZS') }}"
                       class="w-full max-w-xs border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" />
            </div>

            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="font-medium text-gray-900">Line Items</div>
                    <button type="button" @click="addItem"
                            class="px-3 py-1 text-sm rounded-md bg-gray-800 text-white hover:bg-gray-700 transition-colors">
                        Add Item
                    </button>
                </div>
                <template x-for="(item, idx) in items" :key="idx">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-3 p-3 border border-gray-200 rounded-md">
                        <div class="md:col-span-6">
                            <input :name="`items[${idx}][description]`" x-model="item.description"
                                   placeholder="Description"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required />
                        </div>
                        <div class="md:col-span-2">
                            <input type="number" min="1" step="1" :name="`items[${idx}][quantity]`" x-model.number="item.quantity"
                                   placeholder="Qty"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required />
                        </div>
                        <div class="md:col-span-2">
                            <input type="number" min="0" step="0.01" :name="`items[${idx}][unit_price]`" x-model.number="item.unit_price"
                                   placeholder="Unit Price"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent" required />
                        </div>
                        <div class="md:col-span-2 flex items-center justify-between">
                            <div class="text-sm font-medium">Total: <span x-text="(item.quantity * item.unit_price).toFixed(2)"></span></div>
                            <button type="button" @click="removeItem(idx)"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                Remove
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-between items-center border-t pt-4 bg-gray-50 -mx-4 px-4 py-3">
                <div class="text-sm text-gray-600">Subtotal: <span class="font-medium" x-text="subtotal().toFixed(2)"></span></div>
                <div class="text-sm text-gray-600">Tax (0%): <span class="font-medium" x-text="tax().toFixed(2)"></span></div>
                <div class="text-base font-semibold text-gray-900">Grand Total: <span x-text="total().toFixed(2)"></span></div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeInvoiceModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-md hover:bg-emerald-700 transition-colors">
                    Create Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal functions
function openAppointmentModal() {
    document.getElementById('appointmentModal').classList.remove('hidden');
    document.getElementById('appointmentModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeAppointmentModal() {
    document.getElementById('appointmentModal').classList.add('hidden');
    document.getElementById('appointmentModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function openInvoiceModal() {
    document.getElementById('invoiceModal').classList.remove('hidden');
    document.getElementById('invoiceModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeInvoiceModal() {
    document.getElementById('invoiceModal').classList.add('hidden');
    document.getElementById('invoiceModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modals when clicking outside
document.getElementById('appointmentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAppointmentModal();
    }
});

document.getElementById('invoiceModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeInvoiceModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAppointmentModal();
        closeInvoiceModal();
    }
});

// Invoice form Alpine.js component
function invoiceForm(){
    return {
        items: [{ description:'', quantity:1, unit_price:0 }],
        addItem(){ this.items.push({ description:'', quantity:1, unit_price:0 }); },
        removeItem(i){ this.items.splice(i,1); if(this.items.length===0) this.addItem(); },
        subtotal(){ return this.items.reduce((s,i)=> s + (Number(i.quantity||0)*Number(i.unit_price||0)), 0); },
        tax(){ return 0; },
        total(){ return this.subtotal() + this.tax(); }
    }
}
</script>
@endsection
