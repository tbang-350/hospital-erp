@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Invoices</h1>
</div>

<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Invoice #</th>
                    <th class="px-4 py-2 text-left">Patient</th>
                    <th class="px-4 py-2 text-left">Issued</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-4 py-2 font-medium">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-2">{{ $invoice->patient?->first_name }} {{ $invoice->patient?->last_name }}</td>
                    <td class="px-4 py-2">{{ $invoice->issued_date?->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('invoices.show', $invoice) }}" class="text-primary-600 hover:text-primary-800">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No invoices found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $invoices->links() }}
</div>
@endsection