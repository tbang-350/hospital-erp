<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 15);

        $patients = Patient::when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('email', 'like', '%' . request('search') . '%')
                      ->orWhere('phone', 'like', '%' . request('search') . '%');
            })
            ->when(request('gender'), function ($query) {
                $query->where('gender', request('gender'));
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'insurance_number' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'preferred_language' => 'nullable|in:en,sw',
        ]);

        $patient = Patient::create($data);

        // Create notification for new patient
        NotificationService::createPatientAddedNotification($patient);

        return redirect()->route('patients.show', $patient)->with('success', 'Patient created successfully');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments' => function($q){ $q->latest()->limit(10); }, 'invoices' => function($q){ $q->latest()->limit(10); }]);
        return view('patients.show', compact('patient'));
    }
}
