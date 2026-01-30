@extends('layouts.frontend')

@section('content')
@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
@endphp
<div class="min-vh-100 py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="mb-5 text-center" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
            <h1 class="display-5 fw-bold text-charcoal mb-2">{{ __('Shopping Cart') }}</h1>
            <p class="text-stone fs-5 mb-0">{{ __('Review your luxury selections') }}</p>
        </div>

        @if(count($cartItems) > 0)
        <div class="row g-5">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white p-4 border-bottom border-sand">
                        <div class="d-flex align-items-center justify-content-between {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <h5 class="mb-0 fw-bold text-charcoal">{{ __('Cart Items') }} <span class="badge bg-parchment text-terracotta rounded-pill ms-2">{{ count($cartItems) }}</span></h5>
                            <button type="button" class="clear-cart-btn btn btn-link text-stone hover-text-danger text-decoration-none small">
                                <i class="fas fa-trash-alt me-1"></i> {{ __('Clear Cart') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                        <div class="p-4 border-bottom border-light cart-item" data-row-id="{{ $item->rowId }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                            <div class="row align-items-center g-4">
                                <!-- Product Image -->
                                <div class="col-sm-3 col-4">
                                    <div class="position-relative rounded-3 overflow-hidden ratio ratio-1x1 shadow-sm">
                                        @if($item->options->image)
                                            <img src="{{ $item->options->image }}" alt="{{ $item->name }}" class="object-fit-cover w-100 h-100">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-image text-muted fs-2"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="col-sm-9 col-8">
                                    <div class="d-flex justify-content-between mb-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                        <div>
                                            <div class="mb-1">
                                                <span class="badge bg-parchment text-terracotta small">{{ $item->options->category ?? 'Product' }}</span>
                                            </div>
                                            <a href="{{ $item->options->product_slug ? route('products.show', $item->options->product_slug) : '#' }}" class="text-decoration-none text-charcoal fw-bold fs-5 d-block mb-1">
                                                {{ $item->name }}
                                            </a>
                                            <!-- Variant Selector -->
                                            <div class="variant-selector mb-2" data-product-id="{{ $item->options->product_id }}" data-current-variant="{{ $item->id }}">
                                                <div class="dropdown variant-dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="current-variant-name">{{ $item->options->variant_name ?? 'Default' }}</span>
                                                    </button>
                                                    <ul class="dropdown-menu shadow-sm rounded-3">
                                                        <li class="dropdown-header small text-muted">{{ __('Select Variant') }}</li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li class="px-3 py-2 text-center loading-variants">
                                                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="d-block fw-bold text-terracotta fs-5 item-price">${{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-end mt-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                        <!-- Quantity Control -->
                                        <div class="quantity-control">
                                            <div class="input-group input-group-sm border border-secondary rounded-pill overflow-hidden" style="width: 120px;">
                                                <button class="btn btn-light update-quantity px-3" type="button" data-action="decrease">
                                                    <i class="fas fa-minus small"></i>
                                                </button>
                                                <input type="number" class="form-control text-center border-0 bg-white quantity-input p-0" value="{{ $item->qty }}" min="1" max="{{ $item->options->stock }}" readonly>
                                                <button class="btn btn-light update-quantity px-3" type="button" data-action="increase">
                                                    <i class="fas fa-plus small"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Subtotal & Remove -->
                                        <div class="text-end">
                                            <div class="mb-2">
                                                <small class="text-muted d-block">{{ __('Subtotal') }}</small>
                                                <span class="item-subtotal fw-bold text-charcoal">${{ number_format($item->subtotal, 2) }}</span>
                                            </div>
                                            <button type="button" class="btn btn-link link-danger p-0 text-decoration-none small remove-item">
                                                <i class="fas fa-trash me-1"></i> {{ __('Remove') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i> {{ __('Continue Shopping') }}
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow rounded-4 overflow-hidden sticky-top" style="top: 2rem;">
                    <div class="card-header bg-gradient-terracotta text-white p-4">
                        <h4 class="mb-0 fw-bold fs-5">{{ __('Order Summary') }}</h4>
                    </div>
                    <div class="card-body p-4 bg-white">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-stone">{{ __('Subtotal') }}</span>
                            <span class="fw-bold cart-subtotal">${{ $subtotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-stone">{{ __('Tax') }}</span>
                            <span class="fw-bold cart-tax">${{ $tax }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <span class="text-stone">{{ __('Shipping') }}</span>
                            @if(isset($shippingTotal) && $shippingTotal > 0)
                                <div>
                                    <span class="d-block fw-bold text-end cart-shipping">${{ number_format($shippingTotal, 2) }}</span>
                                    <small class="text-muted d-block text-end" style="font-size: 0.75rem;">{{ __('Based on items') }}</small>
                                </div>
                            @else
                                <span class="text-success fw-bold cart-shipping">{{ __('Free') }}</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-stone">{{ __('Discount') }}</span>
                            <span class="text-success fw-bold">-$0.00</span>
                        </div>
                        
                        <hr class="border-sand my-4">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold text-charcoal">{{ __('Total') }}</span>
                            <span class="fs-4 fw-bold text-terracotta cart-total">${{ $total }}</span>
                        </div>
                        
                        <button onclick="window.location.href='{{ route('checkout.index') }}'" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">
                            {{ __('Proceed to Checkout') }} <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        
                        <div class="mt-4 text-center">
                             <div class="d-flex justify-content-center gap-2 text-stone opacity-50">
                                <i class="fab fa-cc-visa fa-2x"></i>
                                <i class="fab fa-cc-mastercard fa-2x"></i>
                                <i class="fab fa-cc-paypal fa-2x"></i>
                            </div>
                            <p class="small text-muted mt-2 mb-0"><i class="fas fa-lock me-1"></i> {{ __('Secure Checkout') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="text-center py-5">
            <div class="mb-4">
                <div class="d-inline-block p-4 rounded-circle bg-parchment text-terracotta mb-3">
                    <i class="fas fa-shopping-basket fa-4x"></i>
                </div>
            </div>
            <h2 class="fw-bold text-charcoal mb-3">{{ __('Your Cart is Empty') }}</h2>
            <p class="text-stone mb-4 lead">{{ __('Looks like you haven\'t added any luxury items yet.') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                {{ __('Start Shopping') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart JS initialized');
    
    // Load variants when dropdown is opened
    document.querySelectorAll('.variant-dropdown').forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        toggle.addEventListener('click', function() {
            const selector = this.closest('.variant-selector');
            const productId = selector.dataset.productId;
            const currentVariant = selector.dataset.currentVariant;
            const menu = dropdown.querySelector('.dropdown-menu');
            
            // Load variants if not already loaded
            if (menu.querySelector('.loading-variants')) {
                loadVariants(productId, currentVariant, menu, selector.closest('.cart-item'));
            }
        });
    });

    // Refresh variants button
    document.querySelectorAll('.refresh-variants').forEach(button => {
        button.addEventListener('click', function() {
            const selector = this.closest('.variant-selector');
            const productId = selector.dataset.productId;
            const currentVariant = selector.dataset.currentVariant;
            const menu = selector.querySelector('.dropdown-menu');
            
            // Reset menu to loading state
            menu.innerHTML = `
                <li class="dropdown-header text-gray-600 small px-3 py-2">
                    <i class="fas fa-palette me-2"></i>{{ __('Select Variant') }}
                </li>
                <li><hr class="dropdown-divider"></li>
                <li class="text-center py-3 loading-variants">
                    <span class="spinner-border spinner-border-sm text-gold me-2"></span>
                    {{ __('Loading variants...') }}
                </li>
            `;
            
            // Animate refresh icon
            this.querySelector('i').classList.add('fa-spin');
            setTimeout(() => {
                this.querySelector('i').classList.remove('fa-spin');
            }, 1000);
            
            loadVariants(productId, currentVariant, menu, selector.closest('.cart-item'));
        });
    });

    // Update quantity - using event delegation for better reliability
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.update-quantity');
        if (!button) return;
        
        console.log('Quantity button clicked');
        const cartItem = button.closest('.cart-item');
        if (!cartItem) {
            console.error('Cart item not found');
            return;
        }
        
        const rowId = cartItem.dataset.rowId;
        const input = cartItem.querySelector('.quantity-input');
        const action = button.dataset.action;
        let currentQty = parseInt(input.value);
        const maxQty = parseInt(input.max) || 99;

        console.log('Current qty:', currentQty, 'Max:', maxQty, 'Action:', action);

        if (action === 'increase' && currentQty < maxQty) {
            currentQty++;
        } else if (action === 'decrease' && currentQty > 1) {
            currentQty--;
        } else {
            console.log('No change needed');
            return;
        }

        updateCartItem(rowId, currentQty, cartItem);
    });

    // Remove item - using event delegation
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.remove-item');
        if (!button) return;
        
        e.preventDefault();
        console.log('Remove button clicked');
        
        const cartItem = button.closest('.cart-item');
        if (!cartItem) {
            console.error('Cart item not found');
            return;
        }
        
        const rowId = cartItem.dataset.rowId;
        console.log('Removing item with rowId:', rowId);
        
        // Use simple confirm for better reliability
        if (confirm('{{ __("Remove this item from your cart?") }}')) {
            removeCartItem(rowId, cartItem);
        }
    });

    // Clear cart button - using event delegation
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.clear-cart-btn');
        if (!button) return;
        
        e.preventDefault();
        console.log('Clear cart button clicked');
        
        if (confirm('{{ __("Are you sure you want to clear all items from your cart?") }}')) {
            clearCart();
        }
    });

    function loadVariants(productId, currentVariant, menu, cartItem) {
        const url = '{{ url(LaravelLocalization::getCurrentLocale() . '/cart/product-variants') }}/' + productId;
        
        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.variants.length > 0) {
                let html = `
                    <li class="dropdown-header text-gray-600 small px-3 py-2">
                        <i class="fas fa-palette me-2"></i>{{ __('Select Variant') }}
                    </li>
                    <li><hr class="dropdown-divider"></li>
                `;
                
                data.variants.forEach(variant => {
                    const isSelected = variant.id == currentVariant;
                    const isDisabled = !variant.in_stock;
                    const stockBadge = variant.in_stock 
                        ? `<span class="badge bg-success bg-opacity-10 text-success ms-auto">${variant.stock} {{ __('in stock') }}</span>`
                        : `<span class="badge bg-danger bg-opacity-10 text-danger ms-auto">{{ __('Out of Stock') }}</span>`;
                    
                    html += `
                        <li>
                            <button type="button" 
                                    class="dropdown-item variant-option d-flex align-items-center gap-2 rounded-2 py-2 px-3 ${isSelected ? 'active bg-primary bg-opacity-10 text-primary' : ''} ${isDisabled ? 'disabled opacity-50' : ''}"
                                    data-variant-id="${variant.id}"
                                    data-variant-name="${variant.name}"
                                    data-variant-price="${variant.price}"
                                    data-variant-stock="${variant.stock}"
                                    ${isDisabled ? 'disabled' : ''}>
                                <div class="flex-fill">
                                    <span class="d-block fw-medium">${variant.name}</span>
                                    <span class="fw-bold">$${variant.price}</span>
                                </div>
                                ${stockBadge}
                                ${isSelected ? '<i class="fas fa-check text-primary"></i>' : ''}
                            </button>
                        </li>
                    `;
                });
                
                menu.innerHTML = html;
                
                // Add click handlers to variant options
                menu.querySelectorAll('.variant-option:not(.disabled)').forEach(option => {
                    option.addEventListener('click', function() {
                        const variantId = this.dataset.variantId;
                        const rowId = cartItem.dataset.rowId;
                        
                        if (variantId != currentVariant) {
                            updateCartVariant(rowId, variantId, cartItem);
                        }
                    });
                });
            } else {
                menu.innerHTML = `
                    <li class="text-center py-3 text-gray-600">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('No variants available') }}
                    </li>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            menu.innerHTML = `
                <li class="text-center py-3 text-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ __('Failed to load variants') }}
                </li>
            `;
        });
    }

    function updateCartVariant(rowId, variantId, cartItem) {
        const url = '{{ route("cart.update.variant", ":rowId") }}'.replace(':rowId', rowId);
        
        // Show loading state
        const selector = cartItem.querySelector('.variant-selector');
        const dropdownBtn = selector.querySelector('.dropdown-toggle');
        const originalHTML = dropdownBtn.innerHTML;
        dropdownBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> {{ __("Updating...") }}';
        dropdownBtn.disabled = true;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ variant_id: variantId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the cart item with new data
                cartItem.dataset.rowId = data.item.rowId;
                selector.dataset.currentVariant = variantId;
                
                // Update variant name and price in dropdown
                dropdownBtn.innerHTML = `
                    <span class="current-variant-name">${data.item.variant_name || 'Default'}</span>
                `;
                dropdownBtn.disabled = false;
                
                // Update price display
                cartItem.querySelector('.item-price').textContent = '$' + data.item.price;
                
                // Update subtotal
                cartItem.querySelector('.item-subtotal').textContent = '$' + data.item.subtotal;
                
                // Update quantity max
                const qtyInput = cartItem.querySelector('.quantity-input');
                qtyInput.max = data.item.stock;
                qtyInput.value = data.item.qty;
                
                // Update stock warning
                const stockWarning = cartItem.querySelector('.stock-warning');
                if (data.item.stock <= 5) {
                    stockWarning.classList.remove('d-none', 'bg-green-50', 'border-green-200');
                    stockWarning.classList.add('bg-red-50', 'border-red-200');
                    stockWarning.querySelector('.stock-text').textContent = `{{ __('Only') }} ${data.item.stock} {{ __('left in stock') }}`;
                    stockWarning.querySelector('i').className = 'fas fa-clock text-red-500';
                } else {
                    stockWarning.classList.add('d-none');
                }
                
                // Update cart totals
                updateCartTotals(data.cart_subtotal, data.cart_total);
                
                // Reset the dropdown menu to reload variants
                const menu = selector.querySelector('.dropdown-menu');
                menu.innerHTML = `
                    <li class="dropdown-header text-gray-600 small px-3 py-2">
                        <i class="fas fa-palette me-2"></i>{{ __('Select Variant') }}
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="text-center py-3 loading-variants">
                        <span class="spinner-border spinner-border-sm text-primary me-2"></span>
                        {{ __('Loading variants...') }}
                    </li>
                `;
                
                showToast(data.message, 'success');
            } else {
                dropdownBtn.innerHTML = originalHTML;
                dropdownBtn.disabled = false;
                showToast(data.message || '{{ __("Failed to update variant") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            dropdownBtn.innerHTML = originalHTML;
            dropdownBtn.disabled = false;
            showToast('{{ __("An error occurred") }}', 'error');
        });
    }

    function updateCartItem(rowId, quantity, cartItem) {
        console.log('Updating cart item:', rowId, 'quantity:', quantity);
        
        const url = '{{ route("cart.update", ":rowId") }}'.replace(':rowId', rowId);
        
        // Show loading state on input
        const input = cartItem.querySelector('.quantity-input');
        input.disabled = true;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => {
            console.log('Update response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Update response data:', data);
            input.disabled = false;
            
            if (data.success) {
                // Update UI with animation
                input.value = quantity;
                
                // Animate price update
                const subtotalElement = cartItem.querySelector('.item-subtotal');
                subtotalElement.style.transform = 'scale(1.1)';
                subtotalElement.style.color = '#D4AF37';
                setTimeout(() => {
                    subtotalElement.textContent = '$' + data.item_subtotal;
                    subtotalElement.style.transform = 'scale(1)';
                    subtotalElement.style.color = '';
                }, 300);
                
                // Update cart totals
                updateCartTotals(data.cart_subtotal, data.cart_total);
                
                // Show success message
                showToast(data.message, 'success');
            } else {
                showToast(data.message || '{{ __("Failed to update cart") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Update error:', error);
            input.disabled = false;
            showToast('{{ __("An error occurred") }}', 'error');
        });
    }

    function removeCartItem(rowId, cartItem) {
        console.log('Removing cart item:', rowId);
        
        const url = '{{ route("cart.remove", ":rowId") }}'.replace(':rowId', rowId);
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Remove response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Remove response data:', data);
            if (data.success) {
                // Animate removal
                cartItem.style.transition = 'all 0.4s ease';
                cartItem.style.opacity = '0';
                cartItem.style.transform = 'translateX(50px)';
                setTimeout(() => {
                    cartItem.remove();
                    
                    // Update cart totals
                    updateCartTotals(data.cart_subtotal, data.cart_total);
                    
                    // Update item count display
                    const itemCountEl = document.querySelector('.item-count-badge');
                    if (itemCountEl) {
                        itemCountEl.textContent = data.cart_count;
                    }
                    
                    // Reload if cart is empty
                    if (data.cart_count === 0) {
                        setTimeout(() => window.location.reload(), 500);
                    }
                }, 400);
                
                showToast(data.message, 'success');
            } else {
                showToast(data.message || '{{ __("Failed to remove item") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Remove error:', error);
            showToast('{{ __("An error occurred") }}', 'error');
        });
    }

    function clearCart() {
        console.log('Clearing cart');
        
        const url = '{{ route("cart.clear") }}';
        
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Clear response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Clear response data:', data);
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 500);
            } else {
                showToast(data.message || '{{ __("Failed to clear cart") }}', 'error');
            }
        })
        .catch(error => {
            console.error('Clear error:', error);
            showToast('{{ __("An error occurred") }}', 'error');
        });
    }

    function updateCartTotals(subtotal, total) {
        const subtotalElement = document.querySelector('.cart-subtotal');
        const totalElement = document.querySelector('.cart-total');
        
        // Animate updates
        [subtotalElement, totalElement].forEach(el => {
            el.style.transform = 'scale(1.05)';
            setTimeout(() => {
                el.textContent = '$' + (el === subtotalElement ? subtotal : total);
                el.style.transform = 'scale(1)';
            }, 150);
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        const bgClass = type === 'success' ? 'bg-gradient-to-r from-emerald-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-red-600';
        toast.className = `position-fixed top-0 end-0 m-4 px-5 py-4 rounded-4 shadow-xxl ${bgClass} text-white d-flex align-items-center gap-3 z-9999`;
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = 'transform 0.3s ease';
        
        const icon = type === 'success' ? 
            '<div class="icon-wrapper bg-white/20 rounded-circle p-2"><i class="fas fa-check"></i></div>' : 
            '<div class="icon-wrapper bg-white/20 rounded-circle p-2"><i class="fas fa-exclamation"></i></div>';
        
        toast.innerHTML = `${icon} <span class="fw-medium">${message}</span>`;
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        // Animate out
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>

<style>
/* Luxury Color Palette */
:root {
    --bs-gold: #D4AF37;
    --bs-gold-dark: #B8860B;
    --bs-gold-soft: #FFF8E1;
    --bs-gray-50: #f9fafb;
    --bs-gray-100: #f3f4f6;
    --bs-gray-200: #e5e7eb;
    --bs-gray-900: #111827;
    --bs-red-50: #fef2f2;
    --bs-red-500: #ef4444;
    --bs-red-700: #b91c1c;
    --bs-blue-50: #eff6ff;
    --bs-blue-600: #2563eb;
    --bs-green-50: #f0fdf4;
    --bs-green-600: #16a34a;
    --bs-purple-50: #faf5ff;
    --bs-purple-600: #9333ea;
    --bs-amber-50: #fffbeb;
    --bs-amber-600: #d97706;
}

/* Luxury Effects */
.shadow-xxl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.shadow-xs {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.letter-spacing-1 {
    letter-spacing: -0.025em;
}

/* Image Effects */
.image-frame {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.image-frame::before {
    content: '';
    position: absolute;
    inset: 0;
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 1rem;
    pointer-events: none;
    z-index: 2;
}

.hover-scale {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-scale:hover {
    transform: scale(1.05);
}

/* Button Effects */
.hover-transform-up:hover {
    transform: translateY(-2px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-dark {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border: none;
}

.btn-dark:hover {
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    transform: translateY(-2px);
}

.hover-bg-gold:hover {
    background-color: var(--bs-gold) !important;
    border-color: var(--bs-gold) !important;
    color: white !important;
}

.hover-bg-gray-900:hover {
    background-color: var(--bs-gray-900) !important;
    border-color: var(--bs-gray-900) !important;
    color: white !important;
}

.hover-bg-danger-soft:hover {
    background-color: var(--bs-red-50) !important;
    border-color: var(--bs-red-500) !important;
    color: var(--bs-red-700) !important;
}

/* Text Effects */
.text-gold {
    color: var(--bs-gold) !important;
}

.text-gold-dark {
    color: var(--bs-gold-dark) !important;
}

.bg-gold-soft {
    background-color: var(--bs-gold-soft) !important;
}

.hover-gold:hover {
    color: var(--bs-gold) !important;
}

.hover-text-danger:hover {
    color: var(--bs-red-500) !important;
}

/* Icon Wrapper */
.icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
}

/* Cart Item Hover */
.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    background: linear-gradient(90deg, rgba(255,248,225,0.1) 0%, rgba(255,255,255,1) 100%);
}

/* Z-index for toast */
.z-9999 {
    z-index: 9999;
}

/* RTL Support */
[dir="rtl"] .fa-chevron-right,
[dir="rtl"] .fa-arrow-right,
[dir="rtl"] .fa-arrow-left {
    transform: rotate(180deg);
}

/* Smooth transitions */
.quantity-input, .item-subtotal, .cart-subtotal, .cart-total {
    transition: all 0.3s ease;
}

/* Max width utility */
.max-w-2xl {
    max-width: 42rem;
}

/* Space utilities */
.space-y-4 > * + * {
    margin-top: 1rem;
}

/* Variant Dropdown Styles */
.variant-dropdown .dropdown-toggle {
    background: white;
    border: 2px solid var(--bs-gray-200);
    transition: all 0.3s ease;
}

.variant-dropdown .dropdown-toggle:hover {
    border-color: var(--bs-gold);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.15);
}

.variant-dropdown .dropdown-menu {
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.variant-dropdown .variant-option {
    cursor: pointer;
    transition: all 0.2s ease;
}

.variant-dropdown .variant-option:hover:not(.disabled) {
    background: var(--bs-gold-soft);
}

.variant-dropdown .variant-option.active {
    background: var(--bs-gold-soft) !important;
    color: var(--bs-gold-dark);
}

.variant-dropdown .variant-option.disabled {
    cursor: not-allowed;
}

.bg-success-soft {
    background-color: rgba(16, 185, 129, 0.1);
}

.bg-danger-soft {
    background-color: rgba(239, 68, 68, 0.1);
}

.refresh-variants {
    transition: all 0.3s ease;
}

.refresh-variants:hover {
    transform: rotate(180deg);
}

/* Additional stock colors */
.bg-green-50 {
    background-color: #f0fdf4;
}

.border-green-200 {
    border-color: #bbf7d0;
}

.text-green-500 {
    color: #22c55e;
}

.text-green-700 {
    color: #15803d;
}
</style>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

/* Alert Dialog Styles */
.alert-dialog-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.alert-dialog-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
}

.alert-dialog-container {
    position: relative;
    z-index: 10000;
    width: 90%;
    max-width: 450px;
}

.alert-dialog-content {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.alert-dialog-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.alert-dialog-icon-success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(34, 197, 94, 0.05));
    color: #10b981;
}

.alert-dialog-icon-warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.05));
    color: #f59e0b;
}

.alert-dialog-icon-danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.05));
    color: #ef4444;
}

.alert-dialog-icon-info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.05));
    color: #3b82f6;
}

.alert-dialog-icon-svg {
    width: 40px;
    height: 40px;
}

.alert-dialog-title {
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 0.75rem;
    color: #1f2937;
}

.alert-dialog-message {
    color: #6b7280;
    text-align: center;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.alert-dialog-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.alert-dialog-actions .btn {
    min-width: 120px;
    padding: 0.625rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.2s;
}

[x-cloak] {
    display: none !important;
}
@endpush

@push('scripts')
<script>
// Alert Dialog Component Data
document.addEventListener('alpine:init', () => {
    Alpine.data('alertDialog', () => ({
        isOpen: false,
        dialogType: 'info',
        dialogTitle: '',
        dialogMessage: '',
        confirmText: 'OK',
        cancelText: 'Cancel',
        showCancel: true,
        onConfirm: null,
        onCancel: null,

        show(options = {}) {
            this.dialogType = options.type || 'info';
            this.dialogTitle = options.title || 'Confirmation';
            this.dialogMessage = options.message || '';
            this.confirmText = options.confirmText || 'OK';
            this.cancelText = options.cancelText || 'Cancel';
            this.showCancel = options.showCancel !== false;
            this.onConfirm = options.onConfirm || null;
            this.onCancel = options.onCancel || null;
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        confirm() {
            if (this.onConfirm && typeof this.onConfirm === 'function') {
                this.onConfirm();
            }
            this.close();
        },

        cancel() {
            if (this.onCancel && typeof this.onCancel === 'function') {
                this.onCancel();
            }
            this.close();
        }
    }));
});

// Global helper function
window.showAlertDialog = function(options) {
    const dialogElement = document.querySelector('[x-data*="alertDialog"]');
    if (dialogElement && dialogElement.__x) {
        dialogElement.__x.$data.show(options);
    }
};
</script>
@endpush

<!-- Alert Dialog Component -->
<div
    x-data="alertDialog"
    x-show="isOpen"
    x-cloak
    class="alert-dialog-overlay"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="close()"
    style="display: none;"
>
    <div class="alert-dialog-backdrop" @click="close()"></div>

    <div
        class="alert-dialog-container"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
    >
        <div class="alert-dialog-content">
            <div class="alert-dialog-icon" :class="{
                'alert-dialog-icon-success': dialogType === 'success',
                'alert-dialog-icon-warning': dialogType === 'warning',
                'alert-dialog-icon-danger': dialogType === 'danger',
                'alert-dialog-icon-info': dialogType === 'info'
            }">
                <template x-if="dialogType === 'success'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="dialogType === 'warning'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </template>
                <template x-if="dialogType === 'danger'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
                <template x-if="dialogType === 'info'">
                    <svg class="alert-dialog-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </div>
            <h3 class="alert-dialog-title" x-text="dialogTitle"></h3>
            <p class="alert-dialog-message" x-text="dialogMessage"></p>
            <div class="alert-dialog-actions">
                <button type="button" class="btn btn-outline-secondary" @click="cancel()" x-show="showCancel">
                    <span x-text="cancelText"></span>
                </button>
                <button type="button" class="btn" :class="{
                    'btn-success': dialogType === 'success',
                    'btn-warning': dialogType === 'warning',
                    'btn-danger': dialogType === 'danger',
                    'btn-clay': dialogType === 'info'
                }" @click="confirm()">
                    <span x-text="confirmText"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection