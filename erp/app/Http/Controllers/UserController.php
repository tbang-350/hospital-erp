<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_users');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles', 'employee'])->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $employees = Employee::whereDoesntHave('user')->get();
        return view('users.create', compact('roles', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'employee_id' => 'nullable|exists:employees,id|unique:users,employee_id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // Assign roles
        $user->roles()->attach($request->roles);

        // Link to employee if provided
        if ($request->employee_id) {
            $employee = Employee::find($request->employee_id);
            $employee->user_id = $user->id;
            $employee->save();
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'employee']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $employees = Employee::whereDoesntHave('user')
            ->orWhere('user_id', $user->id)
            ->get();
        return view('users.edit', compact('user', 'roles', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'employee_id' => ['nullable', 'exists:employees,id', Rule::unique('users', 'employee_id')->ignore($user->id)]
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update roles
        $user->roles()->sync($request->roles);

        // Update employee link
        if ($user->employee && $user->employee->id != $request->employee_id) {
            $user->employee->update(['user_id' => null]);
        }
        
        if ($request->employee_id) {
            $employee = Employee::find($request->employee_id);
            $employee->user_id = $user->id;
            $employee->save();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Unlink employee if exists
        if ($user->employee) {
            $user->employee->update(['user_id' => null]);
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
