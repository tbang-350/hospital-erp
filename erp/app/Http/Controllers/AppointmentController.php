<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Services\NotificationService;
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
        
        // Create notification for new appointment
        NotificationService::createAppointmentNotification($appointment);
        
        return redirect()->route('patients.show', $appointment->patient_id)->with('success', 'Appointment scheduled');
    }

    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'scheduled_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 15);

        $appointments = Appointment::with(['patient', 'employee'])
            ->when(request('search'), function ($query) {
                $query->whereHas('patient', function ($q) {
                    $q->where('first_name', 'like', '%' . request('search') . '%')
                      ->orWhere('last_name', 'like', '%' . request('search') . '%')
                      ->orWhere('mrn', 'like', '%' . request('search') . '%');
                })
                ->orWhere('location', 'like', '%' . request('search') . '%')
                ->orWhere('reason', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return view('appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient','employee']);
        return view('appointments.show', compact('appointment'));
    }
}
