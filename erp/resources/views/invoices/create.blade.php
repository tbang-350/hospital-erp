@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Create Invoice</h1>
<form action="{{ route('invoices.store') }}" method="POST" x-data="invoiceForm()" class="bg-white p-4 rounded border border-gray-200 shadow-sm space-y-4">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patientId }}" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium">Currency</label>
            <input name="currency" value="{{ old('currency','TZS') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <div class="font-medium">Line Items</div>
            <button type="button" @click="addItem" class="px-2 py-1 text-sm rounded bg-gray-800 text-white">Add Item</button>
        </div>
        <template x-for="(item, idx) in items" :key="idx">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-2">
                <div class="md:col-span-6">
                    <input :name="`items[${idx}][description]`" x-model="item.description" placeholder="Description" class="w-full border rounded px-3 py-2" required />
                </div>
                <div class="md:col-span-2">
                    <input type="number" min="1" step="1" :name="`items[${idx}][quantity]`" x-model.number="item.quantity" placeholder="Qty" class="w-full border rounded px-3 py-2" required />
                </div>
                <div class="md:col-span-2">
                    <input type="number" min="0" step="0.01" :name="`items[${idx}][unit_price]`" x-model.number="item.unit_price" placeholder="Unit Price" class="w-full border rounded px-3 py-2" required />
                </div>
                <div class="md:col-span-2 flex items-center justify-between">
                    <div class="text-sm">Total: <span x-text="(item.quantity * item.unit_price).toFixed(2)"></span></div>
                    <button type="button" @click="removeItem(idx)" class="text-red-600 text-sm">Remove</button>
                </div>
            </div>
        </template>
    </div>

    <div class="flex justify-between items-center border-t pt-3">
        <div class="text-sm text-gray-600">Subtotal: <span x-text="subtotal().toFixed(2)"></span></div>
        <div class="text-sm text-gray-600">Tax (0%): <span x-text="tax().toFixed(2)"></span></div>
        <div class="text-base font-semibold">Grand Total: <span x-text="total().toFixed(2)"></span></div>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('patients.show', $patientId) }}" class="px-3 py-2 rounded border">Cancel</a>
        <button class="px-3 py-2 rounded bg-emerald-600 text-white">Create Invoice</button>
    </div>
</form>

<script>
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