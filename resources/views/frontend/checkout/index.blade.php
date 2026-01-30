@extends('layouts.frontend')

@section('title', __('Checkout') . ' - ' . __('NounieStore'))

@section('content')
@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
@endphp
<div class="min-vh-100 bg-linen py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="mb-5" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
            <h1 class="display-4 fw-bold text-charcoal mb-2">{{ __('Checkout') }}</h1>
            <p class="text-stone fs-5">{{ __('Complete your purchase') }}</p>
        </div>

        <form id="checkout-form" class="row g-4">
            @csrf
            
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <!-- Contact Information -->
                <div class="card border-sand rounded-4 shadow-sm overflow-hidden mb-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-header text-white p-4 bg-gradient" style="background: linear-gradient(135deg, var(--bs-clay), var(--bs-terracotta));">
                        <h3 class="fs-5 fw-semibold mb-0 d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-user-circle {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('Contact Information') }}
                        </h3>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('First Name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="first_name" 
                                       value="{{ $customer->first_name ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('Last Name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="last_name" 
                                       value="{{ $customer->last_name ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('Email') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" 
                                       value="{{ $customer->email ?? Auth::user()->email ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('Phone') }} <span class="text-danger">*</span>
                                </label>
                                <input type="tel" name="phone" 
                                       value="{{ $customer->phone_number ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card border-sand rounded-4 shadow-sm overflow-hidden mb-4" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-header text-white p-4 bg-gradient" style="background: linear-gradient(135deg, var(--bs-clay), var(--bs-terracotta));">
                        <h3 class="fs-5 fw-semibold mb-0 d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-map-marker-alt {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('Shipping Address') }}
                        </h3>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-medium text-charcoal">
                                {{ __('Address') }} <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" rows="3" 
                                      class="form-control form-control-lg border-sand rounded-3"
                                      required>{{ $customer->address_line1 ?? '' }}</textarea>
                        </div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('City') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="city" 
                                       value="{{ $customer->city ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('State / Province') }}
                                </label>
                                <input type="text" name="state" 
                                       value="{{ $customer->state ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3">
                            </div>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('Postal Code') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="postal_code" 
                                       value="{{ $customer->postal_code ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3"
                                       required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-charcoal">
                                    {{ __('Country') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="country" 
                                       value="{{ $customer->country ?? '' }}"
                                       class="form-control form-control-lg border-sand rounded-3"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-sand rounded-4 shadow-sm overflow-hidden mb-4" x-data="{ paymentMethod: 'cod' }" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-header text-white p-4 bg-gradient" style="background: linear-gradient(135deg, var(--bs-clay), var(--bs-terracotta));">
                        <h3 class="fs-5 fw-semibold mb-0 d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-credit-card {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('Payment Method') }}
                        </h3>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Cash on Delivery -->
                        <label class="d-flex align-items-center p-4 border border-2 rounded-3 cursor-pointer mb-3 transition-all {{ $isRtl ? 'flex-row-reverse' : '' }}"
                               :class="paymentMethod === 'cod' ? 'border-clay bg-parchment' : 'border-sand hover-border-clay'">
                            <input type="radio" name="payment_method" value="cod" 
                                   x-model="paymentMethod"
                                   checked
                                   class="form-check-input {{ $isRtl ? 'ms-3' : 'me-3' }}" 
                                   style="width: 20px; height: 20px;">
                            <div class="flex-fill">
                                <div class="d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                    <i class="fas fa-money-bill-wave text-success fs-4 {{ $isRtl ? 'ms-3' : 'me-3' }}"></i>
                                    <div>
                                        <span class="fw-bold text-charcoal d-block">{{ __('Cash on Delivery') }}</span>
                                        <p class="small text-stone mb-0">{{ __('Pay with cash when you receive your order') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div x-show="paymentMethod === 'cod'" class="{{ $isRtl ? 'me-2' : 'ms-2' }}">
                                <i class="fas fa-check-circle text-success fs-5"></i>
                            </div>
                        </label>
                        
                        <!-- Stripe Payment -->
                        <label class="d-flex align-items-center p-4 border border-2 rounded-3 cursor-pointer mb-3 transition-all {{ $isRtl ? 'flex-row-reverse' : '' }}"
                               :class="paymentMethod === 'stripe' ? 'border-clay bg-parchment' : 'border-sand hover-border-clay'">
                            <input type="radio" name="payment_method" value="stripe" 
                                   x-model="paymentMethod"
                                   class="form-check-input {{ $isRtl ? 'ms-3' : 'me-3' }}" 
                                   style="width: 20px; height: 20px;">
                            <div class="flex-fill">
                                <div class="d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                    <i class="fab fa-stripe text-primary fs-4 {{ $isRtl ? 'ms-3' : 'me-3' }}"></i>
                                    <div>
                                        <span class="fw-bold text-charcoal d-block">{{ __('Credit / Debit Card') }}</span>
                                        <p class="small text-stone mb-0">{{ __('Secure payment with Stripe') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div x-show="paymentMethod === 'stripe'" class="{{ $isRtl ? 'me-2' : 'ms-2' }}">
                                <i class="fas fa-check-circle text-success fs-5"></i>
                            </div>
                        </label>
                        
                        <!-- Stripe Info -->
                        <div x-show="paymentMethod === 'stripe'" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="alert alert-info border-0 bg-gradient d-flex align-items-center {{ $isRtl ? 'flex-row-reverse' : '' }}"
                             style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05)) !important;">
                            <i class="fas fa-info-circle text-primary {{ $isRtl ? 'ms-2' : 'me-2' }} fs-5"></i>
                            <small class="mb-0">{{ __('You will be redirected to Stripe secure checkout page to complete your payment.') }}</small>
                        </div>
                        
                        <!-- Payment Icons -->
                        <div class="d-flex align-items-center justify-content-center gap-3 pt-3 border-top border-sand">
                            <i class="fab fa-cc-visa text-muted fs-3"></i>
                            <i class="fab fa-cc-mastercard text-muted fs-3"></i>
                            <i class="fab fa-cc-amex text-muted fs-3"></i>
                            <i class="fab fa-cc-discover text-muted fs-3"></i>
                            <i class="fas fa-shield-alt text-success fs-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card border-sand rounded-4 shadow-sm overflow-hidden" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-body p-4">
                        <label class="form-label small fw-medium text-charcoal">
                            <i class="fas fa-sticky-note text-stone {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                            {{ __('Order Notes (Optional)') }}
                        </label>
                        <textarea name="notes" rows="3" 
                                  placeholder="{{ __('Any special instructions for your order?') }}"
                                  class="form-control form-control-lg border-sand rounded-3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-sand rounded-4 shadow-sm overflow-hidden sticky-top" style="top: 100px;" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                    <div class="card-header text-white p-4 bg-gradient" style="background: linear-gradient(135deg, var(--bs-clay), var(--bs-terracotta));">
                        <h3 class="fs-5 fw-semibold mb-0">{{ __('Order Summary') }}</h3>
                    </div>
                    
                    <!-- Cart Items -->
                    <div class="p-4 overflow-auto" style="max-height: 400px;">
                        @foreach($cartItems as $item)
                        <div class="d-flex align-items-center gap-3 mb-3 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }}">
                            <div class="flex-shrink-0 position-relative">
                                @if($item->options->image)
                                <img src="{{ $item->options->image }}" 
                                     alt="{{ $item->name }}" 
                                     class="rounded-3 object-fit-cover" 
                                     style="width: 64px; height: 64px;">
                                @else
                                <div class="rounded-3 d-flex align-items-center justify-content-center bg-sand" style="width: 64px; height: 64px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                                <span class="position-absolute top-0 {{ $isRtl ? 'end-100' : 'start-100' }} translate-middle badge rounded-pill bg-terracotta text-white" style="font-size: 0.75rem;">
                                    {{ $item->qty }}
                                </span>
                            </div>
                            <div class="flex-fill text-truncate">
                                <p class="small fw-medium text-charcoal mb-1 text-truncate">{{ $item->name }}</p>
                                <p class="text-muted" style="font-size: 0.75rem;">${{ number_format($item->price, 2) }} × {{ $item->qty }}</p>
                            </div>
                            <p class="small fw-semibold text-clay text-nowrap">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="px-4 pb-4 border-top border-sand pt-3">
                        <div class="d-flex justify-content-between text-stone mb-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span>{{ __('Subtotal') }}</span>
                            <span class="fw-semibold">${{ $subtotal }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between text-stone mb-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span>{{ __('Tax') }}</span>
                            <span class="fw-semibold">${{ $tax }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between text-stone mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <span>{{ __('Shipping') }}</span>
                            @if(isset($shippingTotal) && $shippingTotal > 0)
                                <span class="fw-semibold">${{ number_format($shippingTotal, 2) }}</span>
                            @else
                                <span class="text-success fw-semibold">{{ __('Free') }}</span>
                            @endif
                        </div>
                        
                        <div class="border-top border-sand pt-3 mb-4">
                            <div class="d-flex justify-content-between fs-5 fw-bold {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <span class="text-charcoal">{{ __('Total') }}</span>
                                <span class="text-terracotta">${{ $total }}</span>
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" 
                                class="btn btn-terracotta btn-lg w-100 d-flex align-items-center justify-content-center gap-2 shadow-lg mb-3 {{ $isRtl ? 'flex-row-reverse' : '' }}"
                                style="padding: 1rem; font-size: 1.125rem;">
                            <i class="fas fa-lock"></i>
                            <span>{{ __('Place Order') }}</span>
                        </button>

                        <!-- Security Badge -->
                        <div class="pt-3 border-top border-sand text-center">
                            <p class="text-muted mb-0" style="font-size: 0.75rem;">
                                <i class="fas fa-shield-alt text-success {{ $isRtl ? 'ms-1' : 'me-1' }}"></i>
                                {{ __('Secure & encrypted checkout') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Disable button and show loading
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> {{ __("Processing...") }}';
    
    // Gather form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Submit order
    fetch('{{ route("checkout.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.payment_method === 'stripe') {
                // Redirect to Stripe checkout
                showToast('{{ __("Redirecting to payment...") }}', 'info');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 500);
            } else {
                // Cash on Delivery - redirect to success page
                showToast(data.message, 'success');
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            }
        } else {
            // Show error message
            showToast(data.message, 'error');
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Checkout error:', error);
        const errorMessage = error.message || error.errors ? Object.values(error.errors || {}).flat().join(', ') : '{{ __("An error occurred. Please try again.") }}';
        showToast(errorMessage, 'error');
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

function showToast(message, type) {
    const toast = document.createElement('div');
    const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
    toast.className = `position-fixed top-0 end-0 m-4 px-4 py-3 rounded-3 shadow-lg ${bgClass} text-white d-flex align-items-center gap-2`;
    toast.style.zIndex = '9999';
    
    const icon = type === 'success' ? 
        '<i class="fas fa-check-circle"></i>' : 
        type === 'error' ? '<i class="fas fa-exclamation-circle"></i>' :
        '<i class="fas fa-info-circle"></i>';
    
    toast.innerHTML = `${icon} <span>${message}</span>`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}

.transition-all {
    transition: all 0.3s ease;
}

label.d-flex:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.hover-border-clay:hover {
    border-color: var(--bs-clay) !important;
}

[x-cloak] {
    display: none !important;
}
</style>
@endpush
@endsection
