@php
    $locale = app()->getLocale();
    $isRtl = in_array($locale, ['ar', 'he', 'fa', 'ur']);
@endphp

@extends('layouts.frontend')

@section('title', __('About Us') . ' - ' . config('app.name'))

@section('content')
<!-- Hero Section -->
<section class="position-relative overflow-hidden  text-white py-5" style="min-height: 400px; background-image: url('{{ asset('assets/about-bg.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed; background-color: rgba(0, 0, 0, 0.5); background-blend-mode: overlay;">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __('About Us') }}
            </span>
            <h1 class="display-3 fw-bold mb-4 animate-fade-in-up animation-delay-100" style="font-family: var(--bs-font-serif);">
                {{ __('About') }} {{ config('app.name') }}
            </h1>
            <p class="lead text-white text-opacity-75 mx-auto mb-0 animate-fade-in-up animation-delay-200" style="max-width: 700px;">
                {{ __('Your trusted destination for quality products and exceptional service') }}
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

<!-- Our Story Section -->
<section class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5 align-items-center" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
            <div class="col-lg-6 animate-fade-in-up">
                <span class="text-terracotta fw-bold text-uppercase small d-block mb-3" style="letter-spacing: 3px;">{{ __('Our Journey') }}</span>
                <h2 class="display-5 fw-bold text-charcoal mb-4" style="font-family: var(--bs-font-serif);">
                    {{ __('Our Story') }}
                </h2>
                <div class="text-stone lh-lg mb-4">
                    <p class="fs-5 mb-4">
                        {{ __('Founded with a passion for excellence, NounieStore has been serving customers worldwide with premium products and unmatched service. Our journey began with a simple mission: to make quality products accessible to everyone.') }}
                    </p>
                    <p class="fs-5 mb-0">
                        {{ __('Today, we continue to uphold our commitment to quality, innovation, and customer satisfaction. Every product in our store is carefully selected to meet our high standards.') }}
                    </p>
                </div>
                <div class="d-flex gap-3 flex-wrap" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <svg width="24" height="24" class="text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-charcoal fw-medium">{{ __('Quality Products') }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <svg width="24" height="24" class="text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-charcoal fw-medium">{{ __('Excellent Service') }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <svg width="24" height="24" class="text-terracotta" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-charcoal fw-medium">{{ __('Trusted Worldwide') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 animate-fade-in-up animation-delay-200">
                <div class="card border-0 shadow-sm overflow-hidden" style="height: 480px;">
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-terracotta position-relative">
                        <img src="{{ asset('assets/our-story.jpg') }}" alt="Our Story" class="h-100 w-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(212, 165, 116, 0.2) 0%, rgba(156, 102, 68, 0.2) 100%);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values Section -->
<section class="py-5 bg-white">
    <div class="container py-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="text-center mb-5 animate-fade-in-up">
            <span class="text-terracotta fw-bold text-uppercase small d-block mb-3" style="letter-spacing: 3px;">{{ __('What We Believe') }}</span>
            <h2 class="display-5 fw-bold text-charcoal mb-4" style="font-family: var(--bs-font-serif);">
                {{ __('Our Values') }}
            </h2>
            <div class="bg-terracotta mx-auto rounded-pill" style="width: 96px; height: 4px;"></div>
        </div>

        <div class="row g-4">
            <!-- Value 1 -->
            <div class="col-md-4 animate-fade-in-up animation-delay-200">
                <div class="card border-0 shadow-sm h-100 card-hover text-center p-4">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10 mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-check text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                            {{ __('Quality First') }}
                        </h3>
                        <p class="text-stone mb-0">
                            {{ __('We never compromise on the quality of our products and services.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Value 2 -->
            <div class="col-md-4 animate-fade-in-up animation-delay-300">
                <div class="card border-0 shadow-sm h-100 card-hover text-center p-4">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10 mb-4" 
                             style="width: 80px; height: 80px;">
                           <i class="fas fa-user-shield text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                            {{ __('Customer Focused') }}
                        </h3>
                        <p class="text-stone mb-0">
                            {{ __('Your satisfaction is our top priority, always.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Value 3 -->
            <div class="col-md-4 animate-fade-in-up animation-delay-400">
                <div class="card border-0 shadow-sm h-100 card-hover text-center p-4">
                    <div class="card-body">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta bg-opacity-10 mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-shipping-fast text-white" style="font-size: 2rem;"></i>
                        </div>
                        <h3 class="h5 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                            {{ __('Fast Delivery') }}
                        </h3>
                        <p class="text-stone mb-0">
                            {{ __('Quick and reliable shipping to your doorstep.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container py-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3 animate-fade-in-up animation-delay-200">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body py-5">
                        <div class="display-3 fw-bold text-terracotta mb-3">10K+</div>
                        <div class="text-stone fw-medium">{{ __('Happy Customers') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 animate-fade-in-up animation-delay-300">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body py-5">
                        <div class="display-3 fw-bold text-terracotta mb-3">5K+</div>
                        <div class="text-stone fw-medium">{{ __('Products') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 animate-fade-in-up animation-delay-400">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body py-5">
                        <div class="display-3 fw-bold text-terracotta mb-3">50+</div>
                        <div class="text-stone fw-medium">{{ __('Countries') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 animate-fade-in-up animation-delay-500">
                <div class="card border-0 shadow-sm h-100 card-hover">
                    <div class="card-body py-5">
                        <div class="display-3 fw-bold text-terracotta mb-3">24/7</div>
                        <div class="text-stone fw-medium">{{ __('Support') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-gradient-charcoal text-white position-relative overflow-hidden">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image:url('{{ asset('assets/shipping.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; background-color: rgba(0, 0, 0, 0.6); background-blend-mode: overlay;"></div>
    <div class="container py-5 position-relative" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="text-center animate-fade-in-up">
            <h2 class="display-5 fw-bold mb-4" style="font-family: var(--bs-font-serif);">
                {{ __('Ready to Start Shopping?') }}
            </h2>
            <p class="lead text-white text-opacity-75 mb-5" style="max-width: 600px; margin-inline: auto;">
                {{ __('Explore our amazing collection of products') }}
            </p>
            <a href="{{ LaravelLocalization::localizeUrl(route('products.index')) }}" 
               class="btn btn-light btn-lg rounded-pill px-5 fw-bold">
                <svg width="20" height="20" class="{{ $isRtl ? 'ms-2' : 'me-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="{{ $isRtl ? 'transform: scaleX(-1);' : '' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                {{ __('Browse Products') }}
            </a>
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
