<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = Employee::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Position filter
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $employees = $query->with(['user', 'payrolls'])
                          ->paginate(10)
                          ->withQueryString();

        // Get stats
        $stats = [
            'total' => Employee::count(),
            'active' => Employee::where('status', 'active')->count(),
            'inactive' => Employee::where('status', 'inactive')->count(),
            'departments' => Employee::distinct('department')->count('department'),
        ];

        $departments = Employee::distinct()->pluck('department')->filter()->sort();
        $positions = Employee::distinct()->pluck('position')->filter()->sort();

        return view('employees.index', compact('employees', 'departments', 'positions', 'stats'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('employee')->get();
        return view('employees.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'employee_id' => 'required|string|unique:employees,employee_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'qualifications' => 'nullable|array',
            'qualifications.*' => 'string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $employee = Employee::create($validated);

            // Create notification
            $this->notificationService->createEmployeeAddedNotification($employee);

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to create employee. Please try again.');
        }
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'payrolls' => function ($query) {
            $query->orderBy('pay_period_start', 'desc')->limit(5);
        }]);

        // Get recent payrolls stats
        $payrollStats = [
            'total_payrolls' => $employee->payrolls()->count(),
            'paid_payrolls' => $employee->payrolls()->where('status', 'paid')->count(),
            'pending_payrolls' => $employee->payrolls()->where('status', 'pending')->count(),
            'total_paid' => $employee->payrolls()->where('status', 'paid')->sum('net_pay'),
        ];

        return view('employees.show', compact('employee', 'payrollStats'));
    }

    public function edit(Employee $employee)
    {
        $users = User::whereDoesntHave('employee')
                    ->orWhere('id', $employee->user_id)
                    ->get();
        return view('employees.edit', compact('employee', 'users'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('employees', 'user_id')->ignore($employee->id)
            ],
            'employee_id' => [
                'required',
                'string',
                Rule::unique('employees', 'employee_id')->ignore($employee->id)
            ],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'qualifications' => 'nullable|array',
            'qualifications.*' => 'string|max:255',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Check if employee has payrolls
        if ($employee->payrolls()->exists()) {
            return back()->with('error', 'Cannot delete employee with existing payroll records.');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
