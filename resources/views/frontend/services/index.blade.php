@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
    $locale = app()->getLocale();
@endphp

@extends('layouts.frontend')

@section('title', __('Services') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden  text-white py-5" style="min-height: 400px; background-image:url('{{ asset('assets/serice-herosectio.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; background-color: rgba(0, 0, 0, 0.6); background-blend-mode: overlay;">
    <!-- Decorative Elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
        <div class="position-absolute rounded-circle animate-pulse-slow" 
             style="width: 40%; height: 40%; background-color: rgba(212, 165, 116, 0.15); filter: blur(80px); top: -10%; left: -10%;"></div>
        <div class="position-absolute rounded-circle animate-pulse-slow" 
             style="width: 40%; height: 40%; background-color: rgba(156, 102, 68, 0.15); filter: blur(80px); bottom: -10%; right: -10%; animation-delay: 2s;"></div>
    </div>

    <div class="container position-relative py-5" style="z-index: 1;">
        <div class="text-center animate-fade-in-up">
            <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-4 py-2 mb-4 border border-white border-opacity-20">
                <svg width="16" height="16" class="me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ __('Professional Services') }}
            </span>
            <h1 class="display-3 fw-bold mb-4 animate-fade-in-up animation-delay-100" style="font-family: var(--bs-font-serif);">
                {{ __('Our Services') }}
            </h1>
            <p class="lead text-white text-opacity-75 mx-auto mb-0 animate-fade-in-up animation-delay-200" style="max-width: 700px;">
                {{ __('Explore our professional services designed to meet your needs') }}
            </p>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="position-absolute bottom-0 start-0 w-100" style="height: 60px; overflow: hidden; line-height: 0; transform: rotate(180deg);">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="height: 100%; width: 100%;">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="#f8f9fa"></path>
        </svg>
    </div>
</section>

<!-- Services Section -->
<section class="py-5 bg-light">
    <div class="container py-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        @if($services->isEmpty())
            <!-- Empty State -->
            <div class="row justify-content-center animate-fade-in-up">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm text-center py-5">
                        <div class="card-body p-5">
                            <svg class="text-stone mb-4" width="96" height="96" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="h4 fw-bold text-charcoal mb-3">{{ __('No services available') }}</h3>
                            <p class="text-stone mb-4">{{ __('Please check back later for new services') }}</p>
                            <a href="{{ LaravelLocalization::localizeUrl(route('home')) }}" 
                               class="btn btn-terracotta btn-lg rounded-pill px-5">
                                <svg width="20" height="20" class="{{ $isRtl ? 'ms-2' : 'me-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="{{ $isRtl ? 'transform: scaleX(-1);' : '' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Back to Home') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Services Grid -->
            <div class="row g-4 mb-4">
                @foreach($services as $service)
                <div class="col-md-6 col-lg-4 animate-fade-in-up animation-delay-{{ ($loop->index % 6) * 100 + 200 }}">
                    <div class="card border-0 shadow-sm h-100 card-hover overflow-hidden">
                        <!-- Service Image -->
                        <div class="position-relative overflow-hidden" style="height: 250px;">
                            @if($service->images && $service->images->count() > 0)
                                <img src="{{ $service->images->first()->url }}" 
                                     alt="{{ $service->images->first()->alt_text ?? (is_array($service->title) ? ($service->title[$locale] ?? $service->title['en'] ?? '') : $service->title) }}" 
                                     class="w-100 h-100 object-fit-cover"
                                     style="transition: transform 0.5s ease;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-terracotta">
                                    <svg width="80" height="80" class="text-white opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Overlay on Hover -->
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 opacity-0 hover-opacity-50" 
                                 style="transition: opacity 0.3s ease;"></div>
                        </div>
                        
                        <!-- Service Info -->
                        <div class="card-body p-4 d-flex flex-column">
                            <h3 class="h5 fw-bold text-charcoal mb-3" style="min-height: 56px; font-family: var(--bs-font-serif);">
                                {{ is_array($service->title) ? ($service->title[$locale] ?? $service->title['en'] ?? '') : $service->title }}
                            </h3>
                            
                            <p class="text-stone mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ is_array($service->description) ? ($service->description[$locale] ?? $service->description['en'] ?? '') : ($service->description ?? '') }}
                            </p>
                            
                            @if($service->price)
                                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                                    <div>
                                        <span class="h4 fw-bold text-terracotta mb-0">
                                            {{ number_format($service->price, 2) }} {{ __('MAD') }}
                                        </span>
                                    </div>
                                    <div class="{{ $isRtl ? 'text-start' : 'text-end' }}">
                                        <svg width="18" height="18" class="text-stone {{ $isRtl ? 'ms-1' : 'me-1' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="small text-stone">
                                            {{ $service->duration }} {{ __('min') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                            <a href="{{ LaravelLocalization::localizeUrl(route('services.show', $service)) }}" 
                               class="btn btn-dark w-100 rounded-pill fw-bold">
                                <svg width="18" height="18" class="{{ $isRtl ? 'ms-2' : 'me-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('Book Now') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
                <div class="card border-0 shadow-sm animate-fade-in-up">
                    <div class="card-body">
                        {{ $services->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-5 bg-white">
    <div class="container py-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="text-center mb-5 animate-fade-in-up">
            <span class="text-terracotta fw-bold text-uppercase small d-block mb-3" style="letter-spacing: 3px;">{{ __('Benefits') }}</span>
            <h2 class="display-5 fw-bold text-charcoal mb-4" style="font-family: var(--bs-font-serif);">
                {{ __('Why Choose Our Services?') }}
            </h2>
            <div class="bg-terracotta mx-auto rounded-pill" style="width: 96px; height: 4px;"></div>
        </div>

        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4 animate-fade-in-up animation-delay-200">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10 mb-4" 
                         style="width: 80px; height: 80px;">
                       i<i class="fas fa-user-shield text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="h5 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                        {{ __('Expert Professionals') }}
                    </h3>
                    <p class="text-stone mb-0">
                        {{ __('Our team consists of highly skilled and experienced professionals.') }}
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-4 animate-fade-in-up animation-delay-300">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10 mb-4" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-check text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="h5 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                        {{ __('Quality Guarantee') }}
                    </h3>
                    <p class="text-stone mb-0">
                        {{ __('We ensure the highest quality in all our services.') }}
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-4 animate-fade-in-up animation-delay-400">
                <div class="text-center p-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10 mb-4" 
                         style="width: 80px; height: 80px;">
                       <i class="fas fa-calendar-alt text-white" style="font-size: 2rem;"></i>
                    </div>
                    <h3 class="h5 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                        {{ __('Flexible Scheduling') }}
                    </h3>
                    <p class="text-stone mb-0">
                        {{ __('Book appointments at your convenience.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .card-hover:hover img {
        transform: scale(1.1);
    }
    
    .hover-opacity-50:hover {
        opacity: 0.5 !important;
    }
</style>
@endpush

@push('scripts')
<script>
// Smooth scroll for animations
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
