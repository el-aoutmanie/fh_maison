@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
    $locale = app()->getLocale();
@endphp

@extends('layouts.frontend')

@section('title', (is_array($service->title) ? ($service->title[$locale] ?? $service->title['en'] ?? '') : $service->title) . ' - ' . config('app.name'))

@section('content')
<!-- Breadcrumb -->
<section class="bg-white border-bottom py-3">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ LaravelLocalization::localizeUrl(route('home')) }}" class="text-decoration-none text-stone">
                        <svg width="16" height="16" class="me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ __('Home') }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ LaravelLocalization::localizeUrl(route('services.index')) }}" class="text-decoration-none text-stone">
                        {{ __('Services') }}
                    </a>
                </li>
                <li class="breadcrumb-item active text-charcoal fw-medium" aria-current="page">
                    {{ Str::limit(is_array($service->title) ? ($service->title[$locale] ?? $service->title['en'] ?? '') : $service->title, 40) }}
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Service Detail -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-4">
            <!-- Left Column - Service Details -->
            <div class="col-lg-8">
                <!-- Service Image -->
                <div class="card border-0 shadow-sm mb-4 overflow-hidden animate-fade-in-up">
                    @if($service->images && $service->images->count() > 0)
                        <img src="{{ $service->images->first()->url }}" 
                             alt="{{ $service->images->first()->alt_text ?? (is_array($service->title) ? ($service->title[$locale] ?? $service->title['en'] ?? '') : $service->title) }}" 
                             class="w-100 object-fit-cover"
                             style="height: 480px;">
                    @else
                        <div class="w-100 d-flex align-items-center justify-content-center bg-gradient-terracotta" style="height: 480px;">
                            <svg width="120" height="120" class="text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Service Info -->
                <div class="card border-0 shadow-sm mb-4 animate-fade-in-up animation-delay-100">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="display-5 fw-bold text-charcoal mb-4" style="font-family: var(--bs-font-serif);">
                            {{ is_array($service->title) ? ($service->title[$locale] ?? $service->title['en'] ?? '') : $service->title }}
                        </h1>

                        <div class="d-flex flex-wrap gap-4 mb-4 pb-4 border-bottom">
                            @if($service->price)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10" 
                                         style="width: 48px; height: 48px;">
                                        <i class="fas fa-dollar-sign text-white"></i>
                                    </div>
                                    <div>
                                        <p class="small text-stone mb-0">{{ __('Price') }}</p>
                                        <p class="h4 fw-bold text-terracotta mb-0">{{ number_format($service->price, 2) }} {{ __('MAD') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if($service->duration)
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10" 
                                         style="width: 48px; height: 48px;">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <p class="small text-stone mb-0">{{ __('Duration') }}</p>
                                        <p class="h5 fw-semibold text-charcoal mb-0">{{ $service->duration }} {{ __('min') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h2 class="h4 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                                {{ __('About This Service') }}
                            </h2>
                            <div class="text-stone lh-lg">
                                {!! nl2br(e(is_array($service->description) ? ($service->description[$locale] ?? $service->description['en'] ?? '') : ($service->description ?? ''))) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="card border-0 shadow-sm animate-fade-in-up animation-delay-200">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10" 
                                 style="width: 48px; height: 48px;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <h2 class="h4 fw-bold text-charcoal mb-0" style="font-family: var(--bs-font-serif);">
                                {{ __('What\'s Included') }}
                            </h2>
                        </div>
                        
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                                <svg width="24" height="24" class="text-success flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-charcoal">{{ __('Professional consultation and guidance') }}</span>
                            </li>
                            <li class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                                <svg width="24" height="24" class="text-success flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-charcoal">{{ __('Flexible scheduling options') }}</span>
                            </li>
                            <li class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                                <svg width="24" height="24" class="text-success flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-charcoal">{{ __('Follow-up support and assistance') }}</span>
                            </li>
                            <li class="d-flex align-items-start gap-3">
                                <svg width="24" height="24" class="text-success flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-charcoal">{{ __('Satisfaction guaranteed') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking Form -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top animate-fade-in-up animation-delay-300" style="top: 100px; z-index: 1;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta" 
                                 style="width: 48px; height: 48px;">
                                <svg width="24" height="24" class="text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="h5 fw-bold text-charcoal mb-0">{{ __('Book This Service') }}</h3>
                        </div>
                        
                        <form action="{{ LaravelLocalization::localizeUrl(route('services.book', $service)) }}" method="POST">
                            @csrf

                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="first_name" class="form-label fw-medium text-charcoal">
                                        {{ __('First Name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="first_name" 
                                           id="first_name" 
                                           required
                                           class="form-control form-control-lg"
                                           placeholder="{{ __('John') }}"
                                           value="{{ old('first_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label fw-medium text-charcoal">
                                        {{ __('Last Name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="last_name" 
                                           id="last_name" 
                                           required
                                           class="form-control form-control-lg"
                                           placeholder="{{ __('Doe') }}"
                                           value="{{ old('last_name') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium text-charcoal">
                                    {{ __('Email') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       required
                                       class="form-control form-control-lg"
                                       placeholder="{{ __('your@email.com') }}"
                                       value="{{ old('email') }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label fw-medium text-charcoal">
                                    {{ __('Phone') }} <span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       name="phone_number" 
                                       id="phone_number" 
                                       required
                                       class="form-control form-control-lg"
                                       placeholder="{{ __('+213 XXX XX XX XX') }}"
                                       value="{{ old('phone_number') }}">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="date" class="form-label fw-medium text-charcoal">
                                        {{ __('Preferred Date') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           name="date" 
                                           id="date" 
                                           required
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="form-control form-control-lg"
                                           value="{{ old('date') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="time" class="form-label fw-medium text-charcoal">
                                        {{ __('Preferred Time') }} <span class="text-danger">*</span>
                                    </label>
                                    <select name="time" id="time" required class="form-select form-select-lg">
                                        <option value="">{{ __('Select time') }}</option>
                                        <option value="09:00" {{ old('time') == '09:00' ? 'selected' : '' }}>09:00 AM</option>
                                        <option value="10:00" {{ old('time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                                        <option value="11:00" {{ old('time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                                        <option value="12:00" {{ old('time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                                        <option value="14:00" {{ old('time') == '14:00' ? 'selected' : '' }}>02:00 PM</option>
                                        <option value="15:00" {{ old('time') == '15:00' ? 'selected' : '' }}>03:00 PM</option>
                                        <option value="16:00" {{ old('time') == '16:00' ? 'selected' : '' }}>04:00 PM</option>
                                        <option value="17:00" {{ old('time') == '17:00' ? 'selected' : '' }}>05:00 PM</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label fw-medium text-charcoal">
                                    {{ __('Additional Notes') }}
                                </label>
                                <textarea name="message" 
                                          id="message" 
                                          rows="3"
                                          class="form-control"
                                          placeholder="{{ __('Any specific requirements or questions...') }}">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" 
                                    class="btn btn-dark btn-lg w-100 rounded-pill fw-bold mb-3">
                                <svg width="20" height="20" class="me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Submit Booking Request') }}
                            </button>

                            <div class="alert alert-light border mb-0 d-flex align-items-start gap-2">
                                <svg width="18" height="18" class="text-terracotta flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <small class="text-stone">
                                    {{ __('We will contact you within 24 hours to confirm your booking.') }}
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-fade-in-up').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush
