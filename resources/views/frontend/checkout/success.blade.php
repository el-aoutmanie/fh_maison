@extends('layouts.frontend')

@section('title', __('Order Confirmed') . ' - ' . __('NounieStore'))

@section('content')
@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
@endphp

<div class="min-vh-100 bg-linen py-5">
    <div class="container">
        <!-- Success Hero Section -->
        <div class="text-center mb-5" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
            <!-- Animated Success Icon -->
            <div class="position-relative d-inline-block mb-4">
                <div class="success-icon-outer rounded-circle d-flex align-items-center justify-content-center mx-auto">
                    <div class="success-icon-inner rounded-circle d-flex align-items-center justify-content-center bg-success shadow-lg">
                        <i class="fas fa-check text-white success-checkmark"></i>
                    </div>
                </div>
                <!-- Decorative Elements -->
                <div class="position-absolute success-sparkle" style="top: 0; {{ $isRtl ? 'left' : 'right' }}: -10px;">
                    <i class="fas fa-sparkles text-terracotta"></i>
                </div>
                <div class="position-absolute success-sparkle" style="bottom: 10px; {{ $isRtl ? 'right' : 'left' }}: -5px; animation-delay: 0.3s;">
                    <i class="fas fa-star text-clay"></i>
                </div>
            </div>
            
            <h1 class="display-4 fw-bold text-charcoal mb-3">{{ __('Order Confirmed!') }}</h1>
            <p class="lead text-stone mb-4 mx-auto" style="max-width: 600px;">
                {{ __('Thank you for your purchase. Your order has been received and is now being processed with care.') }}
            </p>
            
            <!-- Order Code Badge -->
            <div class="d-inline-block bg-parchment rounded-pill px-4 py-2 mb-2">
                <span class="text-stone small">{{ __('Order Number') }}</span>
                <span class="fw-bold text-terracotta ms-2 fs-5">#{{ $order->code }}</span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Order Status Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-header bg-gradient-terracotta text-white p-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <div>
                                <p class="small mb-1 opacity-75">
                                    <i class="far fa-calendar-alt {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>
                                    {{ $order->created_at->format('F j, Y') }} • {{ $order->created_at->format('g:i A') }}
                                </p>
                                <h2 class="h4 mb-0 fw-bold">{{ __('Order Details') }}</h2>
                            </div>
                            <span class="badge bg-white text-terracotta px-3 py-2 rounded-pill fs-6">
                                <i class="fas fa-clock {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>
                                {{ str_replace('_', ' ', ucfirst($order->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Customer & Shipping Info -->
                        <div class="row g-4 mb-4">
                            <!-- Customer Info -->
                            <div class="col-md-6">
                                <div class="bg-parchment p-4 rounded-4 h-100">
                                    <div class="d-flex align-items-center mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                        <div class="bg-terracotta text-white rounded-circle d-flex align-items-center justify-content-center {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 44px; height: 44px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h3 class="h6 fw-bold text-charcoal mb-0">{{ __('Customer Information') }}</h3>
                                    </div>
                                    <div class="{{ $isRtl ? 'pe-5' : 'ps-5' }}">
                                        <p class="fw-semibold text-charcoal mb-2">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                                        <p class="text-stone mb-2 small">
                                            <i class="fas fa-envelope text-clay {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                            {{ $order->customer->email }}
                                        </p>
                                        <p class="text-stone mb-0 small">
                                            <i class="fas fa-phone text-clay {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                            {{ $order->customer->phone_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="col-md-6">
                                <div class="bg-parchment p-4 rounded-4 h-100">
                                    <div class="d-flex align-items-center mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 44px; height: 44px;">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <h3 class="h6 fw-bold text-charcoal mb-0">{{ __('Shipping Address') }}</h3>
                                    </div>
                                    <div class="{{ $isRtl ? 'pe-5' : 'ps-5' }}">
                                        <p class="text-charcoal mb-2 small">{{ $order->customer->address }}</p>
                                        <p class="text-stone mb-2 small">{{ $order->customer->city }}@if($order->customer->state), {{ $order->customer->state }}@endif {{ $order->customer->postal_code }}</p>
                                        <p class="text-stone mb-0 small">
                                            <i class="fas fa-globe-americas text-success {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                            {{ $order->customer->country }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <h3 class="h6 fw-bold text-charcoal mb-0">
                                    <i class="fas fa-shopping-bag text-terracotta {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('Order Items') }}
                                </h3>
                                <span class="badge bg-terracotta rounded-pill">{{ $order->items->count() }} {{ __('items') }}</span>
                            </div>
                            
                            <div class="border border-sand rounded-4 overflow-hidden">
                                @foreach($order->items as $index => $item)
                                <div class="d-flex align-items-center p-3 {{ !$loop->last ? 'border-bottom border-sand' : '' }} {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                    <!-- Product Image -->
                                    <div class="position-relative flex-shrink-0 {{ $isRtl ? 'ms-3' : 'me-3' }}">
                                        @if($item->variant->product->images->first())
                                        <img src="{{ $item->variant->product->images->first()->url }}" 
                                             alt="{{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? '' }}" 
                                             class="rounded-3 object-fit-cover shadow-sm" 
                                             style="width: 70px; height: 70px;">
                                        @else
                                        <div class="bg-parchment rounded-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                            <i class="fas fa-image text-stone"></i>
                                        </div>
                                        @endif
                                        <span class="position-absolute badge bg-terracotta rounded-pill" style="top: -5px; {{ $isRtl ? 'left' : 'right' }}: -5px; font-size: 0.7rem;">
                                            ×{{ $item->quantity }}
                                        </span>
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-grow-1 {{ $isRtl ? 'text-end' : '' }}">
                                        <h5 class="mb-1 fw-semibold text-charcoal" style="font-size: 0.95rem;">
                                            {{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? 'Product' }}
                                        </h5>
                                        @if($item->variant->name)
                                        <p class="text-stone small mb-0">
                                            {{ $item->variant->name[app()->getLocale()] ?? $item->variant->name['en'] ?? '' }}
                                        </p>
                                        @endif
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="{{ $isRtl ? 'me-3 text-start' : 'ms-3 text-end' }}">
                                        <p class="fw-bold text-charcoal mb-0">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                        <p class="text-stone small mb-0">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-4">
                            <div class="d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 50px; height: 50px;">
                                    @if($order->payment_method === 'stripe')
                                    <i class="fab fa-stripe-s fs-5"></i>
                                    @elseif($order->payment_method === 'cash_on_delivery' || $order->payment_method === 'cod')
                                    <i class="fas fa-money-bill-wave"></i>
                                    @else
                                    <i class="fas fa-credit-card"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <p class="small text-success mb-0">{{ __('Payment Method') }}</p>
                                    <p class="fw-bold text-charcoal mb-0">
                                        @if($order->payment_method === 'cod' || $order->payment_method === 'cash_on_delivery')
                                            {{ __('Cash on Delivery') }}
                                        @elseif($order->payment_method === 'stripe')
                                            {{ __('Credit / Debit Card (Stripe)') }}
                                        @else
                                            {{ str_replace('_', ' ', ucwords($order->payment_method)) }}
                                        @endif
                                    </p>
                                </div>
                                <span class="badge bg-success px-3 py-2 rounded-pill {{ $isRtl ? 'me-auto' : 'ms-auto' }}">
                                    <i class="fas fa-check-circle {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>{{ __('Confirmed') }}
                                </span>
                            </div>
                        </div>

                        @if($order->notes)
                        <!-- Order Notes -->
                        <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-4 p-4 mt-3">
                            <div class="d-flex {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <i class="fas fa-sticky-note text-warning {{ $isRtl ? 'ms-3' : 'me-3' }} mt-1"></i>
                                <div>
                                    <h5 class="h6 fw-bold text-charcoal mb-2">{{ __('Order Notes') }}</h5>
                                    <p class="text-stone mb-0">{{ $order->notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h3 class="h5 fw-bold text-charcoal mb-1">{{ __('What happens next?') }}</h3>
                            <p class="text-stone small mb-0">{{ __('Track your order progress') }}</p>
                        </div>
                        
                        <div class="timeline-container">
                            @php
                                $steps = [
                                    ['icon' => 'fa-check', 'title' => __('Order Confirmed'), 'desc' => __('Your order has been received'), 'status' => 'completed'],
                                    ['icon' => 'fa-box-open', 'title' => __('Processing'), 'desc' => __('We are preparing your order'), 'status' => in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : 'pending'],
                                    ['icon' => 'fa-shipping-fast', 'title' => __('Shipped'), 'desc' => __('On the way to you'), 'status' => in_array($order->status, ['shipped', 'delivered']) ? 'completed' : 'pending'],
                                    ['icon' => 'fa-home', 'title' => __('Delivered'), 'desc' => __('Enjoy your purchase!'), 'status' => $order->status === 'delivered' ? 'completed' : 'pending'],
                                ];
                            @endphp
                            
                            <div class="row g-3">
                                @foreach($steps as $index => $step)
                                <div class="col-6 col-md-3">
                                    <div class="text-center timeline-step {{ $step['status'] }}">
                                        <div class="timeline-icon mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center {{ $step['status'] === 'completed' ? 'bg-success text-white' : 'bg-parchment text-stone' }}" style="width: 56px; height: 56px; transition: all 0.3s ease;">
                                            <i class="fas {{ $step['icon'] }}"></i>
                                        </div>
                                        <h4 class="small fw-bold text-charcoal mb-1">{{ $step['title'] }}</h4>
                                        <p class="text-stone mb-0" style="font-size: 0.75rem;">{{ $step['desc'] }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 100px;" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-header bg-charcoal text-white p-4">
                        <h3 class="h5 fw-bold mb-0">{{ __('Order Summary') }}</h3>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="d-flex justify-content-between mb-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="text-stone">{{ __('Subtotal') }}</span>
                            <span class="fw-semibold text-charcoal">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="text-stone">{{ __('Tax') }}</span>
                            <span class="fw-semibold text-charcoal">${{ number_format($order->tax ?? 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="text-stone">{{ __('Shipping') }}</span>
                            @if(($order->shipping_amount ?? 0) > 0)
                                <span class="fw-semibold text-charcoal">${{ number_format($order->shipping_amount, 2) }}</span>
                            @else
                                <span class="text-success fw-semibold">
                                    <i class="fas fa-check-circle {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>{{ __('Free') }}
                                </span>
                            @endif
                        </div>
                        
                        @if(($order->discount_percentage ?? 0) > 0)
                        <div class="d-flex justify-content-between mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="text-stone">{{ __('Discount') }}</span>
                            <span class="text-success fw-semibold">-{{ $order->discount_percentage }}%</span>
                        </div>
                        @endif
                        
                        <hr class="border-sand my-3">
                        
                        <div class="d-flex justify-content-between align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="fw-bold text-charcoal fs-5">{{ __('Total') }}</span>
                            <span class="fw-bold text-terracotta fs-4">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="card-footer bg-parchment border-0 p-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-charcoal w-100 rounded-pill py-3 mb-3 d-flex align-items-center justify-content-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-arrow-{{ $isRtl ? 'right' : 'left' }}"></i>
                            <span>{{ __('Continue Shopping') }}</span>
                        </a>
                        
                        <a href="{{ route('track-order') }}" class="btn btn-terracotta w-100 rounded-pill py-3 d-flex align-items-center justify-content-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-search-location"></i>
                            <span>{{ __('Track Your Order') }}</span>
                        </a>
                    </div>
                </div>
                
                <!-- Help Card -->
                <div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4 text-center">
                        <div class="bg-clay bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-headset text-clay fs-4"></i>
                        </div>
                        <h4 class="h6 fw-bold text-charcoal mb-2">{{ __('Need Help?') }}</h4>
                        <p class="text-stone small mb-3">{{ __('Our support team is here to assist you') }}</p>
                        <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-clay rounded-pill px-4">
                            <i class="fas fa-envelope {{ $isRtl ? 'ms-1' : 'me-1' }}"></i> {{ __('Contact Us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Success Icon Animation */
.success-icon-outer {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(25, 135, 84, 0.05) 100%);
    animation: pulse-ring 2s ease-out infinite;
}

.success-icon-inner {
    width: 90px;
    height: 90px;
}

.success-checkmark {
    font-size: 2.5rem;
    animation: checkmark-pop 0.5s ease-out 0.3s both;
}

.success-sparkle {
    animation: sparkle 1.5s ease-in-out infinite;
}

@keyframes pulse-ring {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes checkmark-pop {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes sparkle {
    0%, 100% {
        opacity: 0.5;
        transform: scale(0.8);
    }
    50% {
        opacity: 1;
        transform: scale(1.2);
    }
}

/* Timeline Styles */
.timeline-step.completed .timeline-icon {
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.timeline-step.pending .timeline-icon {
    opacity: 0.6;
}

/* Button Styles */
.btn-outline-charcoal {
    color: #3A3A3A;
    border-color: #3A3A3A;
    background: transparent;
}

.btn-outline-charcoal:hover {
    color: #fff;
    background-color: #3A3A3A;
    border-color: #3A3A3A;
}

.btn-outline-clay {
    color: #D4A574;
    border-color: #D4A574;
    background: transparent;
}

.btn-outline-clay:hover {
    color: #fff;
    background-color: #D4A574;
    border-color: #D4A574;
}

/* Card hover effects */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Order item hover */
.border-sand > div:hover {
    background-color: rgba(237, 224, 212, 0.3);
}

/* Background color utility */
.bg-charcoal {
    background-color: #3A3A3A !important;
}
</style>
@endpush
@endsection
