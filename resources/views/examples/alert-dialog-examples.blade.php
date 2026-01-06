{{-- Example: Product Card with Alert Dialog --}}
<div class="card product-card">
    <div class="card-body">
        <h5 class="card-title">{{ $product->name }}</h5>
        <p class="card-text">{{ $product->price }}</p>
        
        {{-- Add to Cart with Confirmation --}}
        <button 
            type="button" 
            class="btn btn-clay"
            onclick="confirmAddToCart({{ $product->id }})"
        >
            <i class="fas fa-shopping-cart me-2"></i>
            {{ __('Add to Cart') }}
        </button>
        
        {{-- Add to Wishlist --}}
        <button 
            type="button" 
            class="btn btn-outline-danger"
            onclick="toggleWishlist({{ $product->id }})"
        >
            <i class="far fa-heart"></i>
        </button>
    </div>
</div>

@push('scripts')
<script>
    // Add to Cart with Confirmation Dialog
    function confirmAddToCart(productId) {
        showAlertDialog({
            type: 'info',
            title: '{{ __("Add to Cart") }}',
            message: '{{ __("Do you want to add this item to your cart?") }}',
            confirmText: '{{ __("Add to Cart") }}',
            cancelText: '{{ __("Cancel") }}',
            onConfirm: () => {
                // AJAX call to add to cart
                fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quantity: 1 })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success dialog
                        showAlertDialog({
                            type: 'success',
                            title: '{{ __("Success!") }}',
                            message: '{{ __("Item added to cart successfully!") }}',
                            confirmText: '{{ __("View Cart") }}',
                            cancelText: '{{ __("Continue Shopping") }}',
                            onConfirm: () => {
                                window.location.href = '/cart';
                            }
                        });
                        
                        // Update cart counter
                        updateCartCount();
                    } else {
                        showAlertDialog({
                            type: 'danger',
                            title: '{{ __("Error") }}',
                            message: data.message || '{{ __("Failed to add item to cart") }}',
                            confirmText: '{{ __("OK") }}',
                            showCancel: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlertDialog({
                        type: 'danger',
                        title: '{{ __("Error") }}',
                        message: '{{ __("An error occurred. Please try again.") }}',
                        confirmText: '{{ __("OK") }}',
                        showCancel: false
                    });
                });
            }
        });
    }
    
    // Toggle Wishlist with Confirmation
    function toggleWishlist(productId) {
        const isInWishlist = checkIfInWishlist(productId); // Your logic here
        
        if (isInWishlist) {
            // Confirm removal
            showAlertDialog({
                type: 'warning',
                title: '{{ __("Remove from Wishlist") }}',
                message: '{{ __("Are you sure you want to remove this item from your wishlist?") }}',
                confirmText: '{{ __("Remove") }}',
                cancelText: '{{ __("Cancel") }}',
                onConfirm: () => {
                    removeFromWishlist(productId);
                }
            });
        } else {
            // Add to wishlist
            fetch(`/wishlist/add/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlertDialog({
                        type: 'success',
                        title: '{{ __("Added to Wishlist") }}',
                        message: '{{ __("Item added to your wishlist!") }}',
                        confirmText: '{{ __("View Wishlist") }}',
                        cancelText: '{{ __("Continue") }}',
                        onConfirm: () => {
                            window.location.href = '/wishlist';
                        }
                    });
                }
            });
        }
    }
</script>
@endpush

{{-- Example: Order Cancellation --}}
<form id="cancel-order-{{ $order->id }}" action="{{ route('orders.cancel', $order) }}" method="POST">
    @csrf
    @method('DELETE')
    
    <button 
        type="button" 
        class="btn btn-danger"
        onclick="confirmOrderCancellation({{ $order->id }})"
    >
        {{ __('Cancel Order') }}
    </button>
</form>

@push('scripts')
<script>
    function confirmOrderCancellation(orderId) {
        showAlertDialog({
            type: 'danger',
            title: '{{ __("Cancel Order") }}',
            message: '{{ __("Are you sure you want to cancel this order? This action cannot be undone.") }}',
            confirmText: '{{ __("Yes, Cancel Order") }}',
            cancelText: '{{ __("Keep Order") }}',
            onConfirm: () => {
                document.getElementById(`cancel-order-${orderId}`).submit();
            }
        });
    }
</script>
@endpush

{{-- Example: Logout Confirmation --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<a href="#" onclick="confirmLogout(event)" class="dropdown-item">
    <i class="fas fa-sign-out-alt me-2"></i>
    {{ __('Logout') }}
</a>

@push('scripts')
<script>
    function confirmLogout(event) {
        event.preventDefault();
        
        showAlertDialog({
            type: 'warning',
            title: '{{ __("Logout") }}',
            message: '{{ __("Are you sure you want to logout?") }}',
            confirmText: '{{ __("Yes, Logout") }}',
            cancelText: '{{ __("Cancel") }}',
            onConfirm: () => {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endpush

{{-- Example: Delete Account --}}
<button 
    type="button" 
    class="btn btn-danger"
    onclick="confirmDeleteAccount()"
>
    {{ __('Delete Account') }}
</button>

@push('scripts')
<script>
    function confirmDeleteAccount() {
        showAlertDialog({
            type: 'danger',
            title: '{{ __("Delete Account") }}',
            message: '{{ __("This will permanently delete your account and all data. This action is irreversible. Are you absolutely sure?") }}',
            confirmText: '{{ __("Delete My Account") }}',
            cancelText: '{{ __("Cancel") }}',
            onConfirm: () => {
                // Show second confirmation
                showAlertDialog({
                    type: 'danger',
                    title: '{{ __("Final Confirmation") }}',
                    message: '{{ __("Last chance! Type DELETE to confirm account deletion.") }}',
                    confirmText: '{{ __("Confirm Delete") }}',
                    cancelText: '{{ __("Cancel") }}',
                    onConfirm: () => {
                        // Submit deletion form
                        document.getElementById('delete-account-form').submit();
                    }
                });
            }
        });
    }
</script>
@endpush

{{-- Example: Clear Cart --}}
<button 
    type="button" 
    class="btn btn-outline-danger btn-sm"
    onclick="confirmClearCart()"
>
    <i class="fas fa-trash me-2"></i>
    {{ __('Clear Cart') }}
</button>

@push('scripts')
<script>
    function confirmClearCart() {
        showAlertDialog({
            type: 'warning',
            title: '{{ __("Clear Cart") }}',
            message: '{{ __("Remove all items from your shopping cart?") }}',
            confirmText: '{{ __("Clear Cart") }}',
            cancelText: '{{ __("Cancel") }}',
            onConfirm: () => {
                fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        });
    }
</script>
@endpush

{{-- Example: Apply Coupon with Result --}}
<button 
    type="button" 
    class="btn btn-clay"
    onclick="applyCoupon()"
>
    {{ __('Apply Coupon') }}
</button>

@push('scripts')
<script>
    function applyCoupon() {
        const couponCode = document.getElementById('coupon-code').value;
        
        if (!couponCode) {
            showAlertDialog({
                type: 'warning',
                title: '{{ __("Missing Coupon") }}',
                message: '{{ __("Please enter a coupon code.") }}',
                confirmText: '{{ __("OK") }}',
                showCancel: false
            });
            return;
        }
        
        fetch('/cart/coupon/apply', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ code: couponCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlertDialog({
                    type: 'success',
                    title: '{{ __("Coupon Applied!") }}',
                    message: `{{ __("You saved") }} ${data.discount}!`,
                    confirmText: '{{ __("Great!") }}',
                    showCancel: false,
                    onConfirm: () => {
                        location.reload();
                    }
                });
            } else {
                showAlertDialog({
                    type: 'danger',
                    title: '{{ __("Invalid Coupon") }}',
                    message: data.message || '{{ __("This coupon code is not valid.") }}',
                    confirmText: '{{ __("OK") }}',
                    showCancel: false
                });
            }
        });
    }
</script>
@endpush

{{-- Example: Success After Checkout --}}
@if(session('order_success'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showAlertDialog({
                type: 'success',
                title: '{{ __("Order Placed Successfully!") }}',
                message: '{{ __("Thank you for your order. You will receive a confirmation email shortly.") }}',
                confirmText: '{{ __("View Order") }}',
                cancelText: '{{ __("Continue Shopping") }}',
                onConfirm: () => {
                    window.location.href = '/orders/{{ session("order_id") }}';
                }
            });
        });
    </script>
    @endpush
@endif
