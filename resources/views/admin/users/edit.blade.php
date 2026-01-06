@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Edit User') }}</h2>
        <p class="text-muted mb-0">{{ __('Update user information') }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Users') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Personal Information -->
                <div class="col-12">
                    <h5 class="fw-semibold mb-3 pb-2 border-bottom">
                        <i class="fas fa-user me-2 text-primary"></i>{{ __('Personal Information') }}
                    </h5>
                </div>
                
                <div class="col-md-6">
                    <label for="first_name" class="form-label fw-medium">{{ __('First Name') }} <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="first_name" 
                           id="first_name" 
                           class="form-control @error('first_name') is-invalid @enderror" 
                           value="{{ old('first_name', $user->first_name) }}"
                           required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="last_name" class="form-label fw-medium">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="last_name" 
                           id="last_name" 
                           class="form-control @error('last_name') is-invalid @enderror" 
                           value="{{ old('last_name', $user->last_name) }}"
                           required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="user_name" class="form-label fw-medium">{{ __('Username') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">@</span>
                        <input type="text" 
                               name="user_name" 
                               id="user_name" 
                               class="form-control @error('user_name') is-invalid @enderror" 
                               value="{{ old('user_name', $user->user_name) }}"
                               required>
                    </div>
                    @error('user_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="phone_number" class="form-label fw-medium">{{ __('Phone Number') }}</label>
                    <input type="tel" 
                           name="phone_number" 
                           id="phone_number" 
                           class="form-control @error('phone_number') is-invalid @enderror" 
                           value="{{ old('phone_number', $user->phone_number) }}"
                           placeholder="+212 6XX XXX XXX">
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Account Information -->
                <div class="col-12 mt-4">
                    <h5 class="fw-semibold mb-3 pb-2 border-bottom">
                        <i class="fas fa-envelope me-2 text-primary"></i>{{ __('Account Information') }}
                    </h5>
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label fw-medium">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="role" class="form-label fw-medium">{{ __('Role') }} <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <option value="">{{ __('Select Role') }}</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>{{ __('Manager') }}</option>
                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>{{ __('Customer') }}</option>
                    </select>
                    @if($user->id === auth()->id())
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <div class="form-text text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ __('You cannot change your own role.') }}
                        </div>
                    @endif
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <strong>{{ __('Admin') }}:</strong> {{ __('Full access to all features') }}<br>
                        <strong>{{ __('Manager') }}:</strong> {{ __('Can manage products, orders, and services') }}<br>
                        <strong>{{ __('Customer') }}:</strong> {{ __('Regular customer access') }}
                    </div>
                </div>
                
                <!-- Password -->
                <div class="col-12 mt-4">
                    <h5 class="fw-semibold mb-3 pb-2 border-bottom">
                        <i class="fas fa-lock me-2 text-primary"></i>{{ __('Password') }}
                    </h5>
                    <p class="text-muted small mb-3">{{ __('Leave blank to keep the current password.') }}</p>
                </div>
                
                <div class="col-md-6">
                    <label for="password" class="form-label fw-medium">{{ __('New Password') }}</label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-medium">{{ __('Confirm New Password') }}</label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="form-control">
                </div>
                
                <!-- Status -->
                <div class="col-12 mt-4">
                    <h5 class="fw-semibold mb-3 pb-2 border-bottom">
                        <i class="fas fa-cog me-2 text-primary"></i>{{ __('Settings') }}
                    </h5>
                </div>
                
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input type="checkbox" 
                               name="is_active" 
                               id="is_active" 
                               class="form-check-input" 
                               value="1"
                               {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <label class="form-check-label fw-medium" for="is_active">{{ __('Active Account') }}</label>
                        @if($user->id === auth()->id())
                            <input type="hidden" name="is_active" value="1">
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ __('You cannot deactivate your own account.') }}
                            </div>
                        @else
                            <div class="form-text">{{ __('Inactive accounts cannot log in to the system.') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-between">
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this user? This action cannot be undone.') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger px-4">
                        <i class="fas fa-trash me-2"></i>{{ __('Delete User') }}
                    </button>
                </form>
                @else
                <div></div>
                @endif
                
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>{{ __('Update User') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- User Activity Info -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-3">
            <i class="fas fa-info-circle me-2 text-info"></i>{{ __('Account Information') }}
        </h5>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="text-muted small">{{ __('Account Created') }}</div>
                <div class="fw-medium">{{ $user->created_at->format('M d, Y \a\t h:i A') }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">{{ __('Last Updated') }}</div>
                <div class="fw-medium">{{ $user->updated_at->format('M d, Y \a\t h:i A') }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">{{ __('User ID') }}</div>
                <div class="fw-medium">#{{ $user->id }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
