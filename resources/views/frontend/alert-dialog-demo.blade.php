@extends('layouts.frontend')

@section('title', __('Alert Dialog Demo'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-charcoal mb-3">
                    {{ __('Alert Dialog Component') }}
                </h1>
                <p class="lead text-stone">
                    {{ __('Interactive demonstration of the alert dialog component') }}
                </p>
            </div>

            <!-- Dialog Types -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold text-charcoal mb-4">
                        <i class="fas fa-palette me-2 text-clay"></i>
                        {{ __('Dialog Types') }}
                    </h3>
                    
                    <div class="row g-3">
                        <!-- Success Dialog -->
                        <div class="col-md-6 col-lg-3">
                            <button 
                                type="button" 
                                class="btn btn-success w-100"
                                onclick="showAlertDialog({
                                    type: 'success',
                                    title: '{{ __('Success!') }}',
                                    message: '{{ __('Your operation completed successfully!') }}',
                                    confirmText: '{{ __('Great!') }}',
                                    showCancel: false
                                })"
                            >
                                <i class="fas fa-check-circle me-2"></i>
                                {{ __('Success') }}
                            </button>
                        </div>

                        <!-- Warning Dialog -->
                        <div class="col-md-6 col-lg-3">
                            <button 
                                type="button" 
                                class="btn btn-warning w-100"
                                onclick="showAlertDialog({
                                    type: 'warning',
                                    title: '{{ __('Warning') }}',
                                    message: '{{ __('This action requires your attention.') }}',
                                    confirmText: '{{ __('Proceed') }}',
                                    cancelText: '{{ __('Cancel') }}'
                                })"
                            >
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ __('Warning') }}
                            </button>
                        </div>

                        <!-- Danger Dialog -->
                        <div class="col-md-6 col-lg-3">
                            <button 
                                type="button" 
                                class="btn btn-danger w-100"
                                onclick="showAlertDialog({
                                    type: 'danger',
                                    title: '{{ __('Danger') }}',
                                    message: '{{ __('This action cannot be undone!') }}',
                                    confirmText: '{{ __('Delete') }}',
                                    cancelText: '{{ __('Cancel') }}'
                                })"
                            >
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ __('Danger') }}
                            </button>
                        </div>

                        <!-- Info Dialog -->
                        <div class="col-md-6 col-lg-3">
                            <button 
                                type="button" 
                                class="btn btn-clay w-100"
                                onclick="showAlertDialog({
                                    type: 'info',
                                    title: '{{ __('Info') }}',
                                    message: '{{ __('Here is some important information.') }}',
                                    confirmText: '{{ __('OK') }}',
                                    cancelText: '{{ __('Cancel') }}'
                                })"
                            >
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('Info') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Use Cases -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold text-charcoal mb-4">
                        <i class="fas fa-lightbulb me-2 text-clay"></i>
                        {{ __('Common Use Cases') }}
                    </h3>
                    
                    <div class="row g-3">
                        <!-- Add to Cart -->
                        <div class="col-md-6">
                            <button 
                                type="button" 
                                class="btn btn-outline-clay w-100"
                                onclick="demoAddToCart()"
                            >
                                <i class="fas fa-shopping-cart me-2"></i>
                                {{ __('Add to Cart Demo') }}
                            </button>
                        </div>

                        <!-- Remove Item -->
                        <div class="col-md-6">
                            <button 
                                type="button" 
                                class="btn btn-outline-danger w-100"
                                onclick="demoRemoveItem()"
                            >
                                <i class="fas fa-trash me-2"></i>
                                {{ __('Remove Item Demo') }}
                            </button>
                        </div>

                        <!-- Logout -->
                        <div class="col-md-6">
                            <button 
                                type="button" 
                                class="btn btn-outline-warning w-100"
                                onclick="demoLogout()"
                            >
                                <i class="fas fa-sign-out-alt me-2"></i>
                                {{ __('Logout Demo') }}
                            </button>
                        </div>

                        <!-- Success Message -->
                        <div class="col-md-6">
                            <button 
                                type="button" 
                                class="btn btn-outline-success w-100"
                                onclick="demoSuccess()"
                            >
                                <i class="fas fa-check me-2"></i>
                                {{ __('Success Message Demo') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h3 class="h5 fw-bold text-charcoal mb-4">
                        <i class="fas fa-star me-2 text-clay"></i>
                        {{ __('Features') }}
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('Smooth animations') }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('Backdrop blur effect') }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('ESC key to close') }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('Click outside to close') }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('Mobile responsive') }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('RTL support') }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('Customizable buttons') }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ __('Callback functions') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentation Links -->
            <div class="card border-0 shadow-sm rounded-3 bg-linen">
                <div class="card-body p-4 text-center">
                    <h3 class="h5 fw-bold text-charcoal mb-3">
                        <i class="fas fa-book me-2 text-clay"></i>
                        {{ __('Documentation') }}
                    </h3>
                    <p class="text-stone mb-4">
                        {{ __('Learn more about implementing the alert dialog in your project') }}
                    </p>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="#" class="btn btn-sm btn-clay">
                            <i class="fas fa-file-alt me-2"></i>
                            ALERT_DIALOG_USAGE.md
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-clay">
                            <i class="fas fa-bolt me-2"></i>
                            ALERT_DIALOG_QUICK_REF.md
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-clay">
                            <i class="fas fa-code me-2"></i>
                            alert-dialog-examples.blade.php
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add to Cart Demo
    function demoAddToCart() {
        showAlertDialog({
            type: 'info',
            title: '{{ __("Add to Cart") }}',
            message: '{{ __("Do you want to add this item to your cart?") }}',
            confirmText: '{{ __("Add to Cart") }}',
            cancelText: '{{ __("Cancel") }}',
            onConfirm: () => {
                // Simulate adding to cart
                showAlertDialog({
                    type: 'success',
                    title: '{{ __("Success!") }}',
                    message: '{{ __("Item added to cart successfully!") }}',
                    confirmText: '{{ __("View Cart") }}',
                    cancelText: '{{ __("Continue Shopping") }}'
                });
            }
        });
    }

    // Remove Item Demo
    function demoRemoveItem() {
        showAlertDialog({
            type: 'danger',
            title: '{{ __("Remove Item") }}',
            message: '{{ __("Are you sure you want to remove this item? This action cannot be undone.") }}',
            confirmText: '{{ __("Yes, Remove") }}',
            cancelText: '{{ __("Cancel") }}',
            onConfirm: () => {
                showAlertDialog({
                    type: 'success',
                    title: '{{ __("Removed") }}',
                    message: '{{ __("Item has been removed successfully.") }}',
                    confirmText: '{{ __("OK") }}',
                    showCancel: false
                });
            }
        });
    }

    // Logout Demo
    function demoLogout() {
        showAlertDialog({
            type: 'warning',
            title: '{{ __("Logout") }}',
            message: '{{ __("Are you sure you want to logout from your account?") }}',
            confirmText: '{{ __("Yes, Logout") }}',
            cancelText: '{{ __("Cancel") }}',
            onConfirm: () => {
                showAlertDialog({
                    type: 'success',
                    title: '{{ __("Logged Out") }}',
                    message: '{{ __("You have been successfully logged out.") }}',
                    confirmText: '{{ __("OK") }}',
                    showCancel: false
                });
            }
        });
    }

    // Success Message Demo
    function demoSuccess() {
        showAlertDialog({
            type: 'success',
            title: '{{ __("Order Placed!") }}',
            message: '{{ __("Your order has been successfully placed. You will receive a confirmation email shortly.") }}',
            confirmText: '{{ __("View Order") }}',
            cancelText: '{{ __("Continue Shopping") }}',
            onConfirm: () => {
                console.log('View order clicked');
            },
            onCancel: () => {
                console.log('Continue shopping clicked');
            }
        });
    }
</script>
@endpush
