@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-semibold">{{ $patient->first_name }} {{ $patient->last_name }}</h1>
        <div class="text-sm text-gray-600">MRN: {{ $patient->mrn }}</div>
    </div>
    <div class="flex gap-2">
        <button onclick="openAppointmentModal()" class="px-3 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
            Schedule Appointment
        </button>
        <button onclick="openInvoiceModal()" class="px-3 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">
            Create Invoice
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2 space-y-4">
        <div class="bg-white border rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b font-medium bg-gray-50 rounded-t-lg">Recent Appointments</div>
            <div class="divide-y">
                @forelse($patient->appointments as $a)
                <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $a->scheduled_at?->format('Y-m-d H:i') }}</div>
                            <div class="text-xs text-gray-600 mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $a->status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                                       ($a->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($a->status) }}
                                </span>
                                @if($a->location)
                                    <span class="ml-2">{{ $a->location }}</span>
                                @endif
                                @if($a->reason)
                                    <span class="ml-2">• {{ $a->reason }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-6 text-sm text-gray-500 text-center">No appointments yet.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b font-medium bg-gray-50 rounded-t-lg">Recent Invoices</div>
            <div class="divide-y">
                @forelse($patient->invoices as $inv)
                <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $inv->invoice_number }}</div>
                            <div class="text-xs text-gray-600 mt-1">
                                <span class="font-medium">{{ $inv->currency }} {{ number_format($inv->total,2) }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2
                                    {{ $inv->status === 'paid' ? 'bg-green-100 text-green-800' :
                                       ($inv->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($inv->status) }}
                                </span>
                                @if($inv->issued_date)
                                    <span class="ml-2">• Issued {{ $inv->issued_date->format('Y-m-d') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-4 py-6 text-sm text-gray-500 text-center">No invoices yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white border rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b font-medium bg-gray-50 rounded-t-lg">Demographics</div>
            <div class="p-4 text-sm space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">DOB:</span>
                    <span class="text-gray-900">{{ $patient->dob }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">Gender:</span>
                    <span class="text-gray-900">{{ ucfirst($patient->gender) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">Phone:</span>
                    <span class="text-gray-900">{{ $patient->phone }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">Email:</span>
                    <span class="text-gray-900">{{ $patient->email }}</span>
                </div>
                <div class="border-t pt-3">
                    <div class="text-gray-500 font-medium mb-1">Address:</div>
                    <div class="text-gray-900 text-xs leading-relaxed">{{ $patient->address }}, {{ $patient->city }}, {{ $patient->country }}</div>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 font-medium">Insurance #:</span>
                    <span class="text-gray-900">{{ $patient->insurance_number }}</span>
                </div>
                <div class="border-t pt-3">
                    <div class="text-gray-500 font-medium mb-1">Emergency Contact:</div>
                    <div class="text-gray-900 text-xs">{{ $patient->emergency_contact_name }}</div>
                    <div class="text-gray-600 text-xs">({{ $patient->emergency_contact_phone }})</div>
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
