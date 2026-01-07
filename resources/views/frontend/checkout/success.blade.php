@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <!-- Success Message -->
    <div class="text-center mb-5">
        <div class="d-inline-block position-relative mb-4">
            <div class="rounded-circle bg-success d-inline-flex align-items-center justify-center shadow-lg" style="width: 100px; height: 100px;">
                <i class="fas fa-check text-white" style="font-size: 3rem;"></i>
            </div>
        </div>
        <h1 class="display-4 fw-bold text-success mb-3">{{ __('Order Confirmed!') }}</h1>
        <p class="lead text-muted">
            {{ __('Thank you for your purchase. Your order has been received and is now being processed with care.') }}
        </p>
    </div>

    <!-- Order Details Card -->
    <div class="card shadow-lg border-0 mb-4">
        <!-- Header -->
        <div class="card-header bg-success text-white py-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="badge bg-light text-success mb-2">
                        <i class="fas fa-receipt me-1"></i> {{ __('Order') }}
                    </span>
                    <h2 class="h3 mb-1">#{{ $order->code }}</h2>
                    <p class="mb-0 opacity-75">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ $order->created_at->format('F j, Y') }} • {{ $order->created_at->format('g:i A') }}
                    </p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-warning text-dark px-4 py-2 fs-6">
                        <i class="fas fa-clock me-2"></i>
                        {{ str_replace('_', ' ', ucfirst($order->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="card-body p-4 p-md-5">
            <div class="row mb-4">
                <!-- Customer Info -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="bg-light p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <h3 class="h5 mb-0">{{ __('Customer Information') }}</h3>
                        </div>
                        <p class="fw-bold mb-2">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                        <p class="mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            {{ $order->customer->email }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone text-primary me-2"></i>
                            {{ $order->customer->phone_number }}
                        </p>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="col-lg-6">
                    <div class="bg-light p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="h5 mb-0">{{ __('Shipping Address') }}</h3>
                        </div>
                        <p class="mb-2">{{ $order->customer->address }}</p>
                        <p class="mb-2">{{ $order->customer->city }}, {{ $order->customer->state }} {{ $order->customer->postal_code }}</p>
                        <p class="mb-0">
                            <i class="fas fa-globe-americas text-success me-2"></i>
                            {{ $order->customer->country }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h3 class="h5 mb-0">
                        <i class="fas fa-shopping-bag text-primary me-2"></i>
                        {{ __('Order Items') }}
                    </h3>
                    <span class="badge bg-primary">{{ $order->items->count() }} {{ __('items') }}</span>
                </div>
                
                <div class="list-group">
                    @foreach($order->items as $item)
                    <div class="list-group-item border rounded-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if($item->variant->product->images->first())
                                <img src="{{ $item->variant->product->images->first()->url }}" 
                                     alt="{{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? '' }}" 
                                     class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                                <span class="badge bg-primary position-absolute" style="top: -8px; right: -8px;">{{ $item->quantity }}</span>
                            </div>
                            <div class="col">
                                <h5 class="mb-1">{{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? 'Product' }}</h5>
                                @if($item->variant->name)
                                <p class="text-muted small mb-0">
                                    <span class="badge bg-light text-dark">{{ __('Variant') }}: {{ $item->variant->name[app()->getLocale()] ?? $item->variant->name['en'] ?? '' }}</span>
                                </p>
                                @endif
                            </div>
                            <div class="col-auto text-end">
                                <p class="h5 mb-0">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                <p class="text-muted small mb-0">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="row justify-content-end">
                <div class="col-md-6 col-lg-4">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h4 class="h6 fw-bold mb-3 pb-3 border-bottom">{{ __('Order Summary') }}</h4>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('Subtotal') }}</span>
                                <span class="fw-semibold">${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('Tax') }}</span>
                                <span class="fw-semibold">${{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('Shipping') }}</span>
                                <span class="text-success fw-semibold">
                                    <i class="fas fa-check-circle me-1"></i>{{ __('Free') }}
                                </span>
                            </div>
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ __('Total') }}</span>
                                    <span class="h4 text-success mb-0">${{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="alert alert-success mt-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                        @if($order->payment_method === 'stripe')
                        <i class="fab fa-stripe-s fs-4"></i>
                        @elseif($order->payment_method === 'cash_on_delivery')
                        <i class="fas fa-money-bill-wave fs-5"></i>
                        @else
                        <i class="fas fa-credit-card fs-5"></i>
                        @endif
                    </div>
                    <div>
                        <p class="small text-muted mb-1">{{ __('Payment Method') }}</p>
                        <p class="fw-bold mb-0">{{ str_replace('_', ' ', ucwords($order->payment_method)) }}</p>
                    </div>
                    <span class="badge bg-success ms-auto px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i>{{ __('Confirmed') }}
                    </span>
                </div>
            </div>

            @if($order->notes)
            <!-- Order Notes -->
            <div class="alert alert-warning mt-3" role="alert">
                <div class="d-flex align-items-start">
                    <i class="fas fa-sticky-note text-warning me-3 mt-1"></i>
                    <div>
                        <h5 class="alert-heading h6">{{ __('Order Notes') }}</h5>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Progress Timeline -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-5">
                <h3 class="h4 fw-bold">{{ __('What happens next?') }}</h3>
                <p class="text-muted">{{ __('Track your order progress') }}</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="d-flex flex-column gap-4">
                        <!-- Step 1 -->
                        <div class="d-flex align-items-start">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                <i class="fas fa-check fs-5"></i>
                            </div>
                            <div class="ms-4">
                                <h4 class="h6 fw-bold">{{ __('Order Confirmed') }}</h4>
                                <p class="text-muted mb-0">{{ __('Your order has been received') }}</p>
                            </div>
                        </div>
                        
                        <!-- Step 2 -->
                        <div class="d-flex align-items-start">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                <i class="fas fa-box-open fs-5"></i>
                            </div>
                            <div class="ms-4">
                                <h4 class="h6 fw-bold">{{ __('Processing') }}</h4>
                                <p class="text-muted mb-0">{{ __('We are preparing your order') }}</p>
                            </div>
                        </div>
                        
                        <!-- Step 3 -->
                        <div class="d-flex align-items-start">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                <i class="fas fa-shipping-fast fs-5"></i>
                            </div>
                            <div class="ms-4">
                                <h4 class="h6 fw-bold">{{ __('Shipped') }}</h4>
                                <p class="text-muted mb-0">{{ __('On the way to you') }}</p>
                            </div>
                        </div>
                        
                        <!-- Step 4 -->
                        <div class="d-flex align-items-start">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                <i class="fas fa-home fs-5"></i>
                            </div>
                            <div class="ms-4">
                                <h4 class="h6 fw-bold">{{ __('Delivered') }}</h4>
                                <p class="text-muted mb-0">{{ __('Enjoy your purchase!') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mb-4">
        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg px-5">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Continue Shopping') }}
        </a>
        
        @auth
        <a href="{{ route('orders.index') }}" class="btn btn-success btn-lg px-5">
            <i class="fas fa-list-ul me-2"></i>{{ __('View All Orders') }}
            <i class="fas fa-arrow-right ms-2"></i>
        </a>
        @endauth
    </div>

</div>
@endsection
