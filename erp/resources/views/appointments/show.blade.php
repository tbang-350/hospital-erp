@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-semibold">Appointment</h1>
        <div class="text-sm text-gray-600 dark:text-gray-300">Scheduled {{ $appointment->scheduled_at?->format('Y-m-d H:i') }}</div>
    </div>
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
            {{ $appointment->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' }}">
            {{ ucfirst($appointment->status) }}
        </span>
        <a href="{{ route('appointments.index') }}" class="px-3 py-2 rounded border dark:border-gray-600">Back</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2 space-y-4">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-medium">Details</div>
            <div class="p-4 text-sm space-y-2">
                <div><span class="text-gray-500">Location:</span> {{ $appointment->location }}</div>
                <div><span class="text-gray-500">Reason:</span> {{ $appointment->reason }}</div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-medium">Patient</div>
            <div class="p-4 text-sm space-y-1">
                <div class="font-semibold">{{ $appointment->patient?->first_name }} {{ $appointment->patient?->last_name }}</div>
                <div class="text-gray-600 dark:text-gray-300">MRN: {{ $appointment->patient?->mrn }}</div>
                <a href="{{ route('patients.show', $appointment->patient_id) }}" class="text-primary-600 hover:text-primary-800">View Patient</a>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm">
            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 font-medium">Assigned Staff</div>
            <div class="p-4 text-sm space-y-1">
                @if($appointment->employee)
                    <div class="font-semibold">{{ $appointment->employee->first_name }} {{ $appointment->employee->last_name }}</div>
                    <div class="text-gray-600 dark:text-gray-300">{{ $appointment->employee->position }} - {{ $appointment->employee->department }}</div>
                @else
                    <div class="text-gray-500">Not assigned</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection