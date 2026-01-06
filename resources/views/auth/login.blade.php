<x-guest-layout>
    <div class="text-center mb-4">
        <h3 class="fw-bold text-charcoal mb-1">{{ __('Welcome Back') }}</h3>
        <p class="text-muted small mb-0">{{ __('Sign in to your account to continue') }}</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                   autofocus 
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
            <div class="position-relative">
                <input id="password" 
                       type="password" 
                       class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       placeholder="{{ __('Enter your password') }}">
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        {{-- <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label text-muted small" for="remember_me">
                    {{ __('Remember me') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none text-clay small fw-semibold">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div> --}}

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success  btn-lg w-100 rounded-3 shadow-sm mb-3">
            <i class="fas fa-sign-in-alt me-2"></i>{{ __('Log in') }}
        </button>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center">
                <p class="text-muted small mb-0">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-terracotta fw-semibold text-decoration-none">
                        {{ __('Sign up') }}
                    </a>
                </p>
            </div>
        @endif
    </form>

    <!-- Divider -->
    <div class="position-relative my-4">
        <hr class="text-muted">
        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
            {{ __('Or continue with') }}
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
