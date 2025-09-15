@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>User Details: {{ $user->name }}</h4>
                    <div>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>ID:</strong></div>
                        <div class="col-md-9">{{ $user->id }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Name:</strong></div>
                        <div class="col-md-9">{{ $user->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email:</strong></div>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email Verified:</strong></div>
                        <div class="col-md-9">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Verified on {{ $user->email_verified_at->format('M d, Y H:i') }}
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Not Verified
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Roles:</strong></div>
                        <div class="col-md-9">
                            @forelse($user->roles as $role)
                                <span class="badge bg-primary me-1 mb-1">
                                    {{ $role->display_name }}
                                    <small>({{ $role->name }})</small>
                                </span>
                            @empty
                                <span class="text-muted">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Permissions:</strong></div>
                        <div class="col-md-9">
                            @php
                                $allPermissions = collect();
                                foreach($user->roles as $role) {
                                    if($role->permissions) {
                                        $allPermissions = $allPermissions->merge($role->permissions);
                                    }
                                }
                                $allPermissions = $allPermissions->unique();
                            @endphp
                            
                            @if($allPermissions->count() > 0)
                                <div class="row">
                                    @foreach($allPermissions as $permission)
                                        <div class="col-md-6 mb-1">
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-key"></i> {{ str_replace('_', ' ', ucfirst($permission)) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted">No permissions assigned</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Linked Employee:</strong></div>
                        <div class="col-md-9">
                            @if($user->employee)
                                <div class="card border-success">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-success">
                                            <i class="fas fa-link"></i> {{ $user->employee->first_name }} {{ $user->employee->last_name }}
                                        </h6>
                                        <p class="card-text mb-1">
                                            <strong>Position:</strong> {{ $user->employee->position }}<br>
                                            <strong>Department:</strong> {{ $user->employee->department }}<br>
                                            <strong>Phone:</strong> {{ $user->employee->phone }}<br>
                                            <strong>Email:</strong> {{ $user->employee->email }}
                                        </p>
                                        <a href="{{ route('employees.show', $user->employee) }}" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye"></i> View Employee Details
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> This user is not linked to any employee record.
                                    <a href="{{ route('users.edit', $user) }}" class="alert-link">Link to Employee</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Created At:</strong></div>
                        <div class="col-md-9">{{ $user->created_at->format('M d, Y H:i:s') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Last Updated:</strong></div>
                        <div class="col-md-9">{{ $user->updated_at->format('M d, Y H:i:s') }}</div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit User
                            </a>
                        </div>
                        <div>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection