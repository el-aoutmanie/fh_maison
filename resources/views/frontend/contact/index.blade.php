@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
    $locale = app()->getLocale();
@endphp

@extends('layouts.frontend')

@section('title', __('Contact Us') . ' - ' . config('app.name'))

@section('content')
    <!-- Hero Section -->
    <section class="position-relative overflow-hidden  text-white "
        style="min-height: 400px;background-image: url('{{ asset('assets/heresection.jpg') }}'); background-size: cover; background-position: center;background-repeat: no-repeat;background-attachment: fixed; background-color: rgba(0, 0, 0, 0.6); background-blend-mode: overlay;">
        <!-- Decorative Elements -->
        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
            <div class="position-absolute rounded-circle animate-pulse-slow"
                style="width: 40%; height: 40%; background-color: rgba(212, 165, 116, 0.15); filter: blur(80px); top: -10%; left: -10%;">
            </div>
            <div class="position-absolute rounded-circle animate-pulse-slow"
                style="width: 40%; height: 40%; background-color: rgba(156, 102, 68, 0.15); filter: blur(80px); bottom: -10%; right: -10%; animation-delay: 2s;">
            </div>
        </div>

        <div class="container position-relative py-5" style="z-index: 1;">
            <div class="text-center animate-fade-in-up">
                <span
                    class="badge bg-white bg-opacity-10 text-white rounded-pill px-4 py-2 mb-4 border border-white border-opacity-20">
                    <i class="fas fa-envelope"></i>
                    {{ __('Contact Us') }}
                </span>
                <h1 class="display-3 fw-bold mb-4 animate-fade-in-up animation-delay-100"
                    style="font-family: var(--bs-font-serif);">
                    {{ __('Get in Touch') }}
                </h1>
                <p class="lead text-white text-opacity-75 mx-auto mb-0 animate-fade-in-up animation-delay-200"
                    style="max-width: 700px;">
                    {{ __('Have a question? We\'d love to hear from you.') }}
                </p>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="position-absolute bottom-0 start-0 w-100"
            style="height: 60px; overflow: hidden; line-height: 0; transform: rotate(180deg);">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="height: 100%; width: 100%;">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
                    fill="#f8f9fa"></path>
            </svg>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5 ">
        <div class="container py-4">
            <div class="row g-4">
                <!-- Contact Info Sidebar -->
                <div class="col-lg-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="mb-4 animate-fade-in-up">
                        <h2 class="h3 fw-bold text-charcoal mb-3" style="font-family: var(--bs-font-serif);">
                            {{ __('Contact Information') }}
                        </h2>
                        <p class="text-stone">
                            {{ __('Feel free to reach out to us through any of the following channels.') }}</p>
                    </div>

                    <!-- Contact Cards -->
                    <div class="card border-0 shadow-sm animate-fade-in-up animation-delay-100">
                        <div class="card-body p-4">
                            <!-- Address -->
                            <div class="d-flex align-items-start gap-3 mb-4 pb-4 border-bottom {{ $isRtl ? 'flex-row-reverse text-end' : '' }}">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-terracotta bg-opacity-10 flex-shrink-0"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-map-marker-alt " style="color: white;"></i>
                                </div>
                                <div class="{{ $isRtl ? 'text-end' : '' }}">
                                    <h3 class="h6 fw-bold text-charcoal mb-2">{{ __('Address') }}</h3>
                                    <p class="text-stone small mb-0">{!! __('123 Store Street') !!}<br>{!! __('City, Country 12345') !!}</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="d-flex align-items-start gap-3 mb-4 pb-4 border-bottom {{ $isRtl ? 'flex-row-reverse text-end' : '' }}">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-terracotta bg-opacity-10 flex-shrink-0"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-envelope" style="color: white;"></i>
                                </div>
                                <div class="{{ $isRtl ? 'text-end' : '' }}">
                                    <h3 class="h6 fw-bold text-charcoal mb-2">{{ __('Email') }}</h3>
                                    <p class="text-stone small mb-0">
                                        <a href="mailto:info@nouniestore.com" class="text-decoration-none text-stone">{{ __('info@nouniestore.com') }}</a><br>
                                        <a href="mailto:support@nouniestore.com" class="text-decoration-none text-stone">{{ __('support@nouniestore.com') }}</a>
                                    </p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="d-flex align-items-start gap-3 mb-4 pb-4 border-bottom {{ $isRtl ? 'flex-row-reverse text-end' : '' }}">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-terracotta bg-opacity-10 flex-shrink-0"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-phone" style="color: white;"></i>
                                </div>
                                <div class="{{ $isRtl ? 'text-end' : '' }}">
                                    <h3 class="h6 fw-bold text-charcoal mb-2">{{ __('Phone') }}</h3>
                                    <p class="text-stone small mb-0">
                                        <a href="tel:+12345678900" class="text-decoration-none text-stone">{{ __('+1 (234) 567-8900') }}</a><br>
                                        <a href="tel:+12345678901" class="text-decoration-none text-stone">{{ __('+1 (234) 567-8901') }}</a>
                                    </p>
                                </div>
                            </div>

                            <!-- Business Hours -->
                            <div class="d-flex align-items-start gap-3 {{ $isRtl ? 'flex-row-reverse text-end' : '' }}">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-terracotta bg-opacity-10 flex-shrink-0"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-clock" style="color: white;"></i>
                                </div>
                                <div class="{{ $isRtl ? 'text-end' : '' }}">
                                    <h3 class="h6 fw-bold text-charcoal mb-2">{{ __('Business Hours') }}</h3>
                                    <p class="text-stone small mb-0">
                                        {{ __('Monday - Friday: 9:00 AM - 6:00 PM') }}<br>
                                        {{ __('Saturday: 10:00 AM - 4:00 PM') }}<br>
                                        {{ __('Sunday: Closed') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card border-0 shadow-sm animate-fade-in-up animation-delay-200">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex align-items-center gap-3 mb-4 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-terracotta"
                                    style="width: 48px; height: 48px;">
                                    <svg width="24" height="24" class="text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                </div>
                                <h2 class="h4 fw-bold text-charcoal mb-0" style="font-family: var(--bs-font-serif);">
                                    {{ __('Send us a Message') }}
                                </h2>
                            </div>

                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <form action="{{ LaravelLocalization::localizeUrl(route('contact.store')) }}" method="POST">
                                @csrf

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label fw-medium text-charcoal">
                                            {{ __('First Name') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="first_name" id="first_name" required
                                            class="form-control form-control-lg" placeholder="{{ __('John') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label fw-medium text-charcoal">
                                            {{ __('Last Name') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="last_name" id="last_name" required
                                            class="form-control form-control-lg" placeholder="{{ __('Doe') }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium text-charcoal">
                                        {{ __('Email') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" required
                                        class="form-control form-control-lg" placeholder="{{ __('john@example.com') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-medium text-charcoal">
                                        {{ __('Phone Number') }}
                                    </label>
                                    <input type="tel" name="phone" id="phone"
                                        class="form-control form-control-lg" placeholder="{{ __('+1 (234) 567-8900') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="subject" class="form-label fw-medium text-charcoal">
                                        {{ __('Subject') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="subject" id="subject" required
                                        class="form-control form-control-lg"
                                        placeholder="{{ __('How can we help you?') }}">
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="form-label fw-medium text-charcoal">
                                        {{ __('Message') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="message" id="message" rows="6" required class="form-control"
                                        placeholder="{{ __('Tell us more about your inquiry...') }}"></textarea>
                                </div>

                                <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2">
                                    <span>{{ __('Send Message') }}</span>
                                    <svg width="20" height="20" class="{{ $isRtl ? 'order-first' : '' }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" style="{{ $isRtl ? 'transform: scaleX(-1);' : '' }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
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
