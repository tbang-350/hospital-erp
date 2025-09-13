<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('employee');

        // Period filter
        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'paid') {
                $query->whereNotNull('paid_at');
            } elseif ($request->status === 'pending') {
                $query->whereNull('paid_at');
            }
        }

        // Employee filter
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $payrolls = $query->orderBy('period', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate($request->get('per_page', 15))
                         ->withQueryString();

        $employees = Employee::orderBy('first_name')->get();
        $periods = Payroll::distinct()->pluck('period')->sort()->reverse();

        return view('payrolls.index', compact('payrolls', 'employees', 'periods'));
    }

    public function create(Request $request)
    {
        $employees = Employee::orderBy('first_name')->get();
        $selectedEmployee = null;
        
        if ($request->filled('employee_id')) {
            $selectedEmployee = Employee::find($request->employee_id);
        }
        
        $currentPeriod = now()->format('Y-m');
        
        return view('payrolls.create', compact('employees', 'selectedEmployee', 'currentPeriod'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'meta' => 'nullable|array'
        ]);

        // Check if payroll already exists for this employee and period
        $existingPayroll = Payroll::where('employee_id', $data['employee_id'])
                                 ->where('period', $data['period'])
                                 ->first();

        if ($existingPayroll) {
            return back()->withErrors(['period' => 'Payroll for this employee and period already exists.'])
                        ->withInput();
        }

        $data['allowances'] = $data['allowances'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        
        $payroll = new Payroll($data);
        $payroll->calculateNetPay();
        $payroll->save();

        return redirect()->route('payrolls.show', $payroll)
                        ->with('success', 'Payroll created successfully');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee');
        return view('payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'meta' => 'nullable|array'
        ]);

        // Check if payroll already exists for this employee and period (excluding current)
        $existingPayroll = Payroll::where('employee_id', $data['employee_id'])
                                 ->where('period', $data['period'])
                                 ->where('id', '!=', $payroll->id)
                                 ->first();

        if ($existingPayroll) {
            return back()->withErrors(['period' => 'Payroll for this employee and period already exists.'])
                        ->withInput();
        }

        $data['allowances'] = $data['allowances'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        
        $payroll->fill($data);
        $payroll->calculateNetPay();
        $payroll->save();

        return redirect()->route('payrolls.show', $payroll)
                        ->with('success', 'Payroll updated successfully');
    }

    public function markAsPaid(Payroll $payroll)
    {
        $payroll->update(['paid_at' => now()]);
        
        return back()->with('success', 'Payroll marked as paid successfully');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        
        return redirect()->route('payrolls.index')
                        ->with('success', 'Payroll deleted successfully');
    }

    public function generateBulkPayroll(Request $request)
    {
        $data = $request->validate([
            'period' => 'required|string',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        $created = 0;
        $errors = [];

        foreach ($data['employee_ids'] as $employeeId) {
            $employee = Employee::find($employeeId);
            
            // Check if payroll already exists
            $existingPayroll = Payroll::where('employee_id', $employeeId)
                                     ->where('period', $data['period'])
                                     ->first();

            if ($existingPayroll) {
                $errors[] = "Payroll for {$employee->full_name} already exists for {$data['period']}";
                continue;
            }

            $payroll = new Payroll([
                'employee_id' => $employeeId,
                'period' => $data['period'],
                'basic_salary' => $employee->salary ?? 0,
                'allowances' => 0,
                'deductions' => 0
            ]);
            
            $payroll->calculateNetPay();
            $payroll->save();
            $created++;
        }

        $message = "Successfully created {$created} payroll records.";
        if (!empty($errors)) {
            $message .= ' Errors: ' . implode(', ', $errors);
        }

        return back()->with('success', $message);
    }
}