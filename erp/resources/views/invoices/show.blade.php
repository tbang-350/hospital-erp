@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-semibold">Invoice {{ $invoice->invoice_number }}</h1>
        <div class="text-sm text-gray-600 dark:text-gray-300">Issued {{ $invoice->issued_date?->format('Y-m-d') }}</div>
    </div>
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
            {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' }}">
            {{ ucfirst($invoice->status) }}
        </span>
        <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded border dark:border-gray-600">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2 space-y-4">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-medium">Bill To</div>
            <div class="p-4 text-sm">
                <div class="font-semibold">{{ $invoice->patient?->first_name }} {{ $invoice->patient?->last_name }}</div>
                <div class="text-gray-600 dark:text-gray-300">MRN: {{ $invoice->patient?->mrn }}</div>
                <div class="text-gray-600 dark:text-gray-300">Phone: {{ $invoice->patient?->phone }}</div>
                <div class="text-gray-600 dark:text-gray-300">Email: {{ $invoice->patient?->email }}</div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm overflow-hidden">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-medium">Line Items</div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-right">Qty</th>
                            <th class="px-4 py-2 text-right">Unit Price</th>
                            <th class="px-4 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($invoice->items as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->description }}</td>
                            <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-right">{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-2 text-right">{{ $invoice->currency }} {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">No items.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/30">
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-medium">Subtotal</td>
                            <td class="px-4 py-2 text-right">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-medium">Tax</td>
                            <td class="px-4 py-2 text-right">{{ $invoice->currency }} {{ number_format($invoice->tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-semibold">Total</td>
                            <td class="px-4 py-2 text-right font-semibold">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-medium">Summary</div>
            <div class="p-4 text-sm space-y-1">
                <div><span class="text-gray-500">Status:</span> {{ ucfirst($invoice->status) }}</div>
                <div><span class="text-gray-500">Issued:</span> {{ $invoice->issued_date?->format('Y-m-d') }}</div>
                @if($invoice->due_date)
                <div><span class="text-gray-500">Due:</span> {{ $invoice->due_date->format('Y-m-d') }}</div>
                @endif
                @if($invoice->paid_amount)
                <div><span class="text-gray-500">Paid Amount:</span> {{ $invoice->currency }} {{ number_format($invoice->paid_amount, 2) }}</div>
                @endif
            </div>
        </div>
        <a href="{{ route('patients.show', $invoice->patient_id) }}" class="w-full inline-flex justify-center px-3 py-2 rounded bg-primary-600 text-white hover:bg-primary-700">Back to Patient</a>
    </div>
</div>
@endsection