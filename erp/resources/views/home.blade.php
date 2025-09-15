@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Dashboard') }} - Welcome, {{ Auth::user()->name }}!</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5><i class="fas fa-user"></i> Your Profile</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                    <p><strong>Roles:</strong> 
                                        @foreach(Auth::user()->roles as $role)
                                            <span class="badge bg-primary me-1">{{ $role->display_name }}</span>
                                        @endforeach
                                    </p>
                                    @if(Auth::user()->employee)
                                        <p><strong>Employee:</strong> {{ Auth::user()->employee->first_name }} {{ Auth::user()->employee->last_name }}</p>
                                        <p><strong>Position:</strong> {{ Auth::user()->employee->position }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5><i class="fas fa-key"></i> Your Permissions</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $allPermissions = collect();
                                        foreach(Auth::user()->roles as $role) {
                                            if($role->permissions) {
                                                $allPermissions = $allPermissions->merge($role->permissions);
                                            }
                                        }
                                        $allPermissions = $allPermissions->unique();
                                    @endphp
                                    
                                    @if($allPermissions->count() > 0)
                                        @foreach($allPermissions->take(8) as $permission)
                                            <span class="badge bg-secondary me-1 mb-1">{{ str_replace('_', ' ', ucfirst($permission)) }}</span>
                                        @endforeach
                                        @if($allPermissions->count() > 8)
                                            <span class="text-muted">... and {{ $allPermissions->count() - 8 }} more</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No permissions assigned</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-tachometer-alt"></i> Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if(Auth::user()->hasPermission('manage_users'))
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg w-100">
                                                    <i class="fas fa-users"></i><br>
                                                    Manage Users
                                                </a>
                                            </div>
                                        @endif
                                        @if(Auth::user()->hasPermission('manage_employees'))
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('employees.index') }}" class="btn btn-success btn-lg w-100">
                                                    <i class="fas fa-user-tie"></i><br>
                                                    Manage Employees
                                                </a>
                                            </div>
                                        @endif
                                        @if(Auth::user()->hasPermission('manage_patients'))
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('patients.index') }}" class="btn btn-info btn-lg w-100">
                                                    <i class="fas fa-user-injured"></i><br>
                                                    Manage Patients
                                                </a>
                                            </div>
                                        @endif
                                        @if(Auth::user()->hasPermission('manage_appointments'))
                                            <div class="col-md-3 mb-3">
                                                <a href="{{ route('appointments.index') }}" class="btn btn-warning btn-lg w-100">
                                                    <i class="fas fa-calendar-alt"></i><br>
                                                    Appointments
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
