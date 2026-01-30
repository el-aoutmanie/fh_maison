@extends('layouts.frontend')

@section('title', __('Track Your Order') . ' - ' . __('FH Maison'))

@section('content')
@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
@endphp

<div class="min-vh-100 bg-linen py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="text-center mb-5" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
            <div class="d-inline-block bg-terracotta bg-opacity-10 rounded-circle p-4 mb-4">
                <i class="fas fa-search-location text-terracotta fa-3x"></i>
            </div>
            <h1 class="display-5 fw-bold text-charcoal mb-3">{{ __('Track Your Order') }}</h1>
            <p class="lead text-stone mb-0 mx-auto" style="max-width: 600px;">
                {{ __('Enter your order code to track your delivery status and view order details.') }}
            </p>
        </div>

        <!-- Search Form -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4 p-md-5">
                        @if(session('error'))
                        <div class="alert alert-danger rounded-3 mb-4 d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-exclamation-circle {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                            {{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ route('track-order.search') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-charcoal mb-2">
                                    <i class="fas fa-receipt text-terracotta {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('Order Code') }}
                                </label>
                                <input type="text" 
                                       name="order_code" 
                                       class="form-control form-control-lg border-sand rounded-3 @error('order_code') is-invalid @enderror"
                                       placeholder="{{ __('e.g., ORD-67890ABCDE') }}"
                                       value="{{ old('order_code', request('order_code')) }}"
                                       required
                                       autofocus>
                                @error('order_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-stone small mt-2">
                                    <i class="fas fa-info-circle {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('You can find your order code in your confirmation email.') }}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-terracotta btn-lg w-100 rounded-pill d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-search"></i>
                                <span>{{ __('Track Order') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($order))
        <!-- Order Found - Display Details -->
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
                                <h2 class="h4 mb-0 fw-bold">#{{ $order->code }}</h2>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-warning text-dark',
                                    'processing' => 'bg-info text-white',
                                    'shipped' => 'bg-primary text-white',
                                    'delivered' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                ];
                                $statusColor = $statusColors[$order->status] ?? 'bg-secondary text-white';
                            @endphp
                            <span class="badge {{ $statusColor }} px-3 py-2 rounded-pill fs-6">
                                <i class="fas fa-{{ $order->status === 'delivered' ? 'check-circle' : ($order->status === 'shipped' ? 'truck' : ($order->status === 'processing' ? 'cog fa-spin' : ($order->status === 'cancelled' ? 'times-circle' : 'clock'))) }} {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>
                                {{ __(ucfirst($order->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Order Timeline -->
                        <div class="mb-4">
                            <h3 class="h6 fw-bold text-charcoal mb-4">
                                <i class="fas fa-route text-terracotta {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('Order Progress') }}
                            </h3>
                            
                            @php
                                $steps = [
                                    ['key' => 'pending', 'icon' => 'fa-receipt', 'title' => __('Order Placed'), 'desc' => __('Your order has been received')],
                                    ['key' => 'processing', 'icon' => 'fa-box-open', 'title' => __('Processing'), 'desc' => __('We are preparing your order')],
                                    ['key' => 'shipped', 'icon' => 'fa-shipping-fast', 'title' => __('Shipped'), 'desc' => __('On the way to you')],
                                    ['key' => 'delivered', 'icon' => 'fa-home', 'title' => __('Delivered'), 'desc' => __('Order completed')],
                                ];
                                
                                $statusOrder = ['pending' => 0, 'processing' => 1, 'shipped' => 2, 'delivered' => 3, 'cancelled' => -1];
                                $currentStatus = $statusOrder[$order->status] ?? 0;
                            @endphp
                            
                            <div class="position-relative">
                                <!-- Progress Line -->
                                <div class="position-absolute bg-sand" style="height: 4px; top: 28px; left: 40px; right: 40px; z-index: 0;"></div>
                                @if($order->status !== 'cancelled')
                                <div class="position-absolute bg-success" style="height: 4px; top: 28px; left: 40px; width: {{ min(100, ($currentStatus / 3) * 100) }}%; z-index: 1; transition: width 0.5s ease;"></div>
                                @endif
                                
                                <div class="row g-3 position-relative" style="z-index: 2;">
                                    @foreach($steps as $index => $step)
                                    @php
                                        $isCompleted = $currentStatus >= $index && $order->status !== 'cancelled';
                                        $isCurrent = $currentStatus === $index && $order->status !== 'cancelled';
                                    @endphp
                                    <div class="col-3">
                                        <div class="text-center">
                                            <div class="mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center shadow-sm {{ $isCompleted ? 'bg-success text-white' : 'bg-white text-stone border border-sand' }}" 
                                                 style="width: 56px; height: 56px; transition: all 0.3s ease;">
                                                @if($isCompleted && !$isCurrent)
                                                <i class="fas fa-check"></i>
                                                @else
                                                <i class="fas {{ $step['icon'] }} {{ $isCurrent ? 'fa-pulse' : '' }}"></i>
                                                @endif
                                            </div>
                                            <h4 class="small fw-bold text-charcoal mb-1">{{ $step['title'] }}</h4>
                                            <p class="text-stone mb-0 d-none d-md-block" style="font-size: 0.7rem;">{{ $step['desc'] }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($order->status === 'cancelled')
                            <div class="alert alert-danger mt-4 rounded-3 d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <i class="fas fa-times-circle {{ $isRtl ? 'ms-2' : 'me-2' }} fs-5"></i>
                                <div>
                                    <strong>{{ __('Order Cancelled') }}</strong>
                                    <p class="mb-0 small">{{ __('This order has been cancelled.') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Tracking Info (if shipped) -->
                        @if($order->status === 'shipped' || $order->status === 'delivered')
                        @if($order->tracking_number || $order->tracking_url)
                        <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 p-4 mb-4">
                            <div class="d-flex align-items-start {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 44px; height: 44px;">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h4 class="h6 fw-bold text-charcoal mb-2">{{ __('Shipping Information') }}</h4>
                                    @if($order->tracking_number)
                                    <p class="mb-1">
                                        <span class="text-stone">{{ __('Tracking Number:') }}</span>
                                        <span class="fw-bold text-charcoal ms-2">{{ $order->tracking_number }}</span>
                                    </p>
                                    @endif
                                    @if($order->shipped_at)
                                    <p class="mb-1 small text-stone">
                                        <i class="far fa-calendar {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('Shipped on:') }} {{ $order->shipped_at->format('F j, Y') }}
                                    </p>
                                    @endif
                                    @if($order->tracking_url)
                                    <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-sm btn-primary rounded-pill mt-2">
                                        <i class="fas fa-external-link-alt {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>
                                        {{ __('Track Shipment') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif

                        <!-- Delivery Info (if delivered) -->
                        @if($order->status === 'delivered' && $order->delivered_at)
                        <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-4 mb-4">
                            <div class="d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 44px; height: 44px;">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div>
                                    <h4 class="h6 fw-bold text-charcoal mb-1">{{ __('Delivered Successfully') }}</h4>
                                    <p class="mb-0 text-stone small">
                                        {{ __('Delivered on:') }} {{ $order->delivered_at->format('F j, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Order Items -->
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <h3 class="h6 fw-bold text-charcoal mb-0">
                                    <i class="fas fa-shopping-bag text-terracotta {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                    {{ __('Order Items') }}
                                </h3>
                                <span class="badge bg-terracotta rounded-pill">{{ $order->items->count() }} {{ __('items') }}</span>
                            </div>
                            
                            <div class="border border-sand rounded-4 overflow-hidden">
                                @foreach($order->items as $item)
                                <div class="d-flex align-items-center p-3 {{ !$loop->last ? 'border-bottom border-sand' : '' }} {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                    <div class="position-relative flex-shrink-0 {{ $isRtl ? 'ms-3' : 'me-3' }}">
                                        @if($item->variant->product->images->first())
                                        <img src="{{ $item->variant->product->images->first()->url }}" 
                                             alt="{{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? '' }}" 
                                             class="rounded-3 object-fit-cover shadow-sm" 
                                             style="width: 60px; height: 60px;">
                                        @else
                                        <div class="bg-parchment rounded-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-stone"></i>
                                        </div>
                                        @endif
                                        <span class="position-absolute badge bg-terracotta rounded-pill" style="top: -5px; {{ $isRtl ? 'left' : 'right' }}: -5px; font-size: 0.65rem;">
                                            ×{{ $item->quantity }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex-grow-1 {{ $isRtl ? 'text-end' : '' }}">
                                        <h5 class="mb-1 fw-semibold text-charcoal" style="font-size: 0.9rem;">
                                            {{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? 'Product' }}
                                        </h5>
                                        @if($item->variant->name)
                                        <p class="text-stone small mb-0">
                                            {{ $item->variant->name[app()->getLocale()] ?? $item->variant->name['en'] ?? '' }}
                                        </p>
                                        @endif
                                    </div>
                                    
                                    <div class="{{ $isRtl ? 'me-3 text-start' : 'ms-3 text-end' }}">
                                        <p class="fw-bold text-charcoal mb-0">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-header bg-charcoal text-white p-4">
                        <h3 class="h6 fw-bold mb-0">{{ __('Order Summary') }}</h3>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="d-flex justify-content-between mb-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="text-stone">{{ __('Subtotal') }}</span>
                            <span class="fw-semibold text-charcoal">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                        </div>
                        @if(($order->tax ?? 0) > 0)
                        <div class="d-flex justify-content-between mb-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="text-stone">{{ __('Tax') }}</span>
                            <span class="fw-semibold text-charcoal">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="text-stone">{{ __('Shipping') }}</span>
                            @if(($order->shipping_amount ?? 0) > 0)
                                <span class="fw-semibold text-charcoal">${{ number_format($order->shipping_amount, 2) }}</span>
                            @else
                                <span class="text-success fw-semibold">{{ __('Free') }}</span>
                            @endif
                        </div>
                        
                        <hr class="border-sand my-3">
                        
                        <div class="d-flex justify-content-between align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span class="fw-bold text-charcoal">{{ __('Total') }}</span>
                            <span class="fw-bold text-terracotta fs-5">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 40px; height: 40px;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="h6 fw-bold text-charcoal mb-0">{{ __('Shipping Address') }}</h3>
                        </div>
                        <div class="{{ $isRtl ? 'pe-5' : 'ps-5' }}">
                            <p class="fw-semibold text-charcoal mb-1">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                            <p class="text-stone small mb-1">{{ $order->customer->address }}</p>
                            <p class="text-stone small mb-1">{{ $order->customer->city }}@if($order->customer->state), {{ $order->customer->state }}@endif {{ $order->customer->postal_code }}</p>
                            <p class="text-stone small mb-0">{{ $order->customer->country }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <div class="bg-terracotta text-white rounded-circle d-flex align-items-center justify-content-center {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 40px; height: 40px;">
                                @if($order->payment_method === 'stripe')
                                <i class="fab fa-stripe-s"></i>
                                @else
                                <i class="fas fa-money-bill-wave"></i>
                                @endif
                            </div>
                            <h3 class="h6 fw-bold text-charcoal mb-0">{{ __('Payment Method') }}</h3>
                        </div>
                        <div class="{{ $isRtl ? 'pe-5' : 'ps-5' }}">
                            <p class="text-charcoal mb-0">
                                @if($order->payment_method === 'cod' || $order->payment_method === 'cash_on_delivery')
                                    {{ __('Cash on Delivery') }}
                                @elseif($order->payment_method === 'stripe')
                                    {{ __('Credit / Debit Card') }}
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4 text-center">
                        <div class="bg-clay bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-headset text-clay fs-5"></i>
                        </div>
                        <h4 class="h6 fw-bold text-charcoal mb-2">{{ __('Need Help?') }}</h4>
                        <p class="text-stone small mb-3">{{ __('Contact our support team') }}</p>
                        <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-clay rounded-pill px-4">
                            <i class="fas fa-envelope {{ $isRtl ? 'ms-1' : 'me-1' }}"></i> {{ __('Contact Us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- No Order Found - Show Tips -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="h5 fw-bold text-charcoal mb-4 text-center">
                            <i class="fas fa-lightbulb text-warning {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('Tips for tracking your order') }}
                        </h3>
                        <div class="row g-4">
                            <div class="col-md-4 text-center">
                                <div class="bg-parchment rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-envelope-open-text text-terracotta fs-4"></i>
                                </div>
                                <h4 class="h6 fw-bold text-charcoal mb-2">{{ __('Check Your Email') }}</h4>
                                <p class="text-stone small mb-0">{{ __('Your order code was sent to your email after purchase.') }}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="bg-parchment rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-barcode text-terracotta fs-4"></i>
                                </div>
                                <h4 class="h6 fw-bold text-charcoal mb-2">{{ __('Order Code Format') }}</h4>
                                <p class="text-stone small mb-0">{{ __('Order codes start with "ORD-" followed by letters and numbers.') }}</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="bg-parchment rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-headset text-terracotta fs-4"></i>
                                </div>
                                <h4 class="h6 fw-bold text-charcoal mb-2">{{ __('Need Help?') }}</h4>
                                <p class="text-stone small mb-0">{{ __('Contact our support team if you can\'t find your order code.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
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

/* Animation for current step */
.fa-pulse {
    animation: fa-pulse 1s ease infinite;
}

@keyframes fa-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
@endpush
@endsection
