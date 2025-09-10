<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function create(Patient $patient)
    {
        return view('appointments.create', compact('patient'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'employee_id' => 'nullable|exists:employees,id',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'reason' => 'nullable|string',
        ]);
        $data['status'] = 'scheduled';
        $appointment = Appointment::create($data);
        return redirect()->route('patients.show', $appointment->patient_id)->with('success', 'Appointment scheduled');
    }

    public function index()
    {
        $appointments = Appointment::with(['patient','employee'])->latest()->paginate(10);
        return view('appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient','employee']);
        return view('appointments.show', compact('appointment'));
    }
}
