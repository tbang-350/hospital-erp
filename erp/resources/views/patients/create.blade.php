@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-semibold mb-4">New Patient</h1>
<form action="{{ route('patients.store') }}" method="POST" class="bg-white p-4 rounded border border-gray-200 shadow-sm space-y-4">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">First name</label>
            <input name="first_name" value="{{ old('first_name') }}" class="mt-1 w-full border rounded px-3 py-2" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Last name</label>
            <input name="last_name" value="{{ old('last_name') }}" class="mt-1 w-full border rounded px-3 py-2" required />
        </div>
        <div>
            <label class="block text-sm font-medium">Date of birth</label>
            <input type="date" name="dob" value="{{ old('dob') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Gender</label>
            <select name="gender" class="mt-1 w-full border rounded px-3 py-2">
                <option value="">--</option>
                <option value="male" @selected(old('gender')==='male')>Male</option>
                <option value="female" @selected(old('gender')==='female')>Female</option>
                <option value="other" @selected(old('gender')==='other')>Other</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Phone</label>
            <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium">Address</label>
            <input name="address" value="{{ old('address') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">City</label>
            <input name="city" value="{{ old('city') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Country</label>
            <input name="country" value="{{ old('country', 'Tanzania') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Insurance Number</label>
            <input name="insurance_number" value="{{ old('insurance_number') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Emergency Contact Name</label>
            <input name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Emergency Contact Phone</label>
            <input name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" class="mt-1 w-full border rounded px-3 py-2" />
        </div>
        <div>
            <label class="block text-sm font-medium">Preferred Language</label>
            <select name="preferred_language" class="mt-1 w-full border rounded px-3 py-2">
                <option value="en" @selected(old('preferred_language','en')==='en')>English</option>
                <option value="sw" @selected(old('preferred_language')==='sw')>Swahili</option>
            </select>
        </div>
    </div>
    <div class="flex justify-end gap-2">
        <a href="{{ route('patients.index') }}" class="px-3 py-2 rounded border">Cancel</a>
        <button class="px-3 py-2 rounded bg-blue-600 text-white">Save</button>
    </div>
</form>
@endsection