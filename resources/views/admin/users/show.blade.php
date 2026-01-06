@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('User Details') }}</h2>
        <p class="text-muted mb-0">{{ __('View user information') }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>{{ __('Edit User') }}
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Users') }}
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- User Profile Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center mx-auto mb-3" 
                     style="width: 100px; height: 100px; background: linear-gradient(135deg, #D4A574, #9C6644);">
                    <span class="text-white fw-bold fs-1">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                </div>
                
                <h4 class="fw-bold mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                <p class="text-muted mb-3">{{ '@' . $user->user_name }}</p>
                
                @if($user->role === 'admin')
                    <span class="badge bg-danger px-3 py-2">{{ __('Admin') }}</span>
                @elseif($user->role === 'manager')
                    <span class="badge bg-warning text-dark px-3 py-2">{{ __('Manager') }}</span>
                @else
                    <span class="badge bg-info px-3 py-2">{{ __('Customer') }}</span>
                @endif
                
                <div class="mt-3">
                    @if($user->is_active)
                        <span class="badge bg-success-subtle text-success px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>{{ __('Active') }}
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i>{{ __('Inactive') }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">{{ __('Quick Actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>{{ __('Edit User') }}
                    </a>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i>{{ __('Delete User') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-user me-2 text-primary"></i>{{ __('Personal Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('First Name') }}</label>
                            <span class="fw-medium">{{ $user->first_name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Last Name') }}</label>
                            <span class="fw-medium">{{ $user->last_name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Username') }}</label>
                            <span class="fw-medium">{{ '@' . $user->user_name }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Phone Number') }}</label>
                            <span class="fw-medium">
                                @if($user->phone_number)
                                    <a href="tel:{{ $user->phone_number }}" class="text-decoration-none">
                                        {{ $user->phone_number }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('Not provided') }}</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-envelope me-2 text-primary"></i>{{ __('Account Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Email Address') }}</label>
                            <a href="mailto:{{ $user->email }}" class="fw-medium text-decoration-none">
                                {{ $user->email }}
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Role') }}</label>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">{{ __('Admin') }}</span>
                            @elseif($user->role === 'manager')
                                <span class="badge bg-warning text-dark">{{ __('Manager') }}</span>
                            @else
                                <span class="badge bg-info">{{ __('Customer') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Account Status') }}</label>
                            @if($user->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('User ID') }}</label>
                            <span class="fw-medium">#{{ $user->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-clock me-2 text-primary"></i>{{ __('Activity') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Account Created') }}</label>
                            <span class="fw-medium">{{ $user->created_at->format('M d, Y') }}</span>
                            <span class="text-muted small d-block">{{ $user->created_at->format('h:i A') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Last Updated') }}</label>
                            <span class="fw-medium">{{ $user->updated_at->format('M d, Y') }}</span>
                            <span class="text-muted small d-block">{{ $user->updated_at->format('h:i A') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">{{ __('Account Age') }}</label>
                            <span class="fw-medium">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
