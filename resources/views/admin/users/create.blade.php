@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Create User') }}</h2>
        <p class="text-muted mb-0">{{ __('Add a new user to the system') }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Users') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
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
                           value="{{ old('first_name') }}"
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
                           value="{{ old('last_name') }}"
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
                               value="{{ old('user_name') }}"
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
                           value="{{ old('phone_number') }}"
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
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="role" class="form-label fw-medium">{{ __('Role') }} <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">{{ __('Select Role') }}</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>{{ __('Manager') }}</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>{{ __('Customer') }}</option>
                    </select>
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
                </div>
                
                <div class="col-md-6">
                    <label for="password" class="form-label fw-medium">{{ __('Password') }} <span class="text-danger">*</span></label>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           class="form-control @error('password') is-invalid @enderror"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-medium">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation" 
                           class="form-control"
                           required>
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
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="is_active">{{ __('Active Account') }}</label>
                        <div class="form-text">{{ __('Inactive accounts cannot log in to the system.') }}</div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save me-2"></i>{{ __('Create User') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
