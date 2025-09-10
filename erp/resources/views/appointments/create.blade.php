@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">Schedule Appointment for {{ $patient->first_name }} {{ $patient->last_name }}</h1>
<form action="{{ route('appointments.store') }}" method="POST" class="bg-white p-4 rounded border border-gray-200 shadow-sm space-y-4">
    @csrf
    <input type="hidden" name="patient_id" value="{{ $patient->id }}" />
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Date & Time</label>
            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="mt-1 w-full border rounded px-3 py-2" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Location</label>
            <input name="location" value="{{ old('location') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium">Reason</label>
            <textarea name="reason" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('reason') }}</textarea>
        </div>
    </div>
    <div class="flex justify-end gap-2">
        <a href="{{ route('patients.show', $patient) }}" class="px-3 py-2 rounded border">Cancel</a>
        <button class="px-3 py-2 rounded bg-indigo-600 text-white">Schedule</button>
    </div>
</form>
@endsection