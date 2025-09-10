@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Appointments</h1>
</div>

<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Scheduled</th>
                    <th class="px-4 py-2 text-left">Patient</th>
                    <th class="px-4 py-2 text-left">Staff</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Location</th>
                    <th class="px-4 py-2 text-left">Reason</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($appointments as $a)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-4 py-2">{{ $a->scheduled_at?->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-2">{{ $a->patient?->first_name }} {{ $a->patient?->last_name }}</td>
                    <td class="px-4 py-2">{{ $a->employee?->first_name }} {{ $a->employee?->last_name }}</td>
                    <td class="px-4 py-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            {{ $a->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' }}">
                            {{ ucfirst($a->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $a->location }}</td>
                    <td class="px-4 py-2">{{ 
                        Str::limit($a->reason ?? '', 40) 
                    }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('appointments.show', $a) }}" class="text-primary-600 hover:text-primary-800">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No appointments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $appointments->links() }}
</div>
@endsection