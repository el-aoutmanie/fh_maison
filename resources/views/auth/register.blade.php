<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="fw-bold text-charcoal mb-1">{{ __('Create Account') }}</h3>
        <p class="text-muted small mb-0">{{ __('Join us to start shopping handcrafted items') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold text-charcoal">
                <i class="fas fa-user me-2 text-clay"></i>{{ __('Full Name') }}
            </label>
            <input id="name" 
                   type="text" 
                   class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   placeholder="{{ __('Enter your full name') }}">
            @error('name')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold text-charcoal">
                <i class="fas fa-envelope me-2 text-clay"></i>{{ __('Email') }}
            </label>
            <input id="email" 
                   type="email" 
                   class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="username"
                   placeholder="{{ __('Enter your email') }}">
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold text-charcoal">
                <i class="fas fa-lock me-2 text-clay"></i>{{ __('Password') }}
            </label>
            <input id="password" 
                   type="password" 
                   class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror" 
                   name="password" 
                   required 
                   autocomplete="new-password"
                   placeholder="{{ __('Create a password') }}">
            @error('password')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold text-charcoal">
                <i class="fas fa-lock me-2 text-clay"></i>{{ __('Confirm Password') }}
            </label>
            <input id="password_confirmation" 
                   type="password" 
                   class="form-control form-control-lg rounded-3" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password"
                   placeholder="{{ __('Confirm your password') }}">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-terracotta btn-lg w-100 rounded-3 shadow-sm mb-3">
            <i class="fas fa-user-plus me-2"></i>{{ __('Create Account') }}
        </button>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-muted small mb-0">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="text-terracotta fw-semibold text-decoration-none">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>
    </form>

    <!-- Divider -->
    <div class="position-relative my-4">
        <hr class="text-muted">
        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
            {{ __('Or sign up with') }}
        </span>
    </div>

    <!-- Social Login Buttons -->
    <div class="row g-2">
        <div class="col-6">
            <button type="button" class="btn btn-outline-secondary w-100 rounded-3" disabled>
                <i class="fab fa-google me-2"></i>{{ __('Google') }}
            </button>
        </div>
        <div class="col-6">
            <button type="button" class="btn btn-outline-secondary w-100 rounded-3" disabled>
                <i class="fab fa-facebook-f me-2"></i>{{ __('Facebook') }}
            </button>
        </div>
    </div>
</x-guest-layout>
