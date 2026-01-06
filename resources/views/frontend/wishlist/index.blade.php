@extends('layouts.frontend')

@section('content')
    <div class="min-vh-100 bg-gradient-to-br from-gray-50 via-white to-amber-50/30 py-8">
        <div class="container">
            <!-- Premium Page Header with Stats -->
            <div class="mb-10">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-4">
                        <div class="p-3 bg-gradient-to-r from-rose-500 to-pink-500 rounded-3 shadow-lg">
                            <i class="fas fa-heart text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-gray-600 fs-5 mb-0 d-flex align-items-center">
                            <i class="fas fa-star text-amber-400 me-2"></i>
                            {{ __('Your curated collection of favorite items') }}
                        </p>
                    </div>
                </div>

                <!-- Filter & Sort Bar -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <span class="text-sm text-gray-500 me-3">{{ __('Sort by:') }}</span>
                                    <select class="form-select form-select-sm border-0 bg-light" style="max-width: 200px;"
                                        onchange="sortWishlist(this.value)">
                                        <option value="recent">{{ __('Recently Added') }}</option>
                                        <option value="price-low">{{ __('Price: Low to High') }}</option>
                                        <option value="price-high">{{ __('Price: High to Low') }}</option>
                                        <option value="name">{{ __('Name A-Z') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-gray-400"
                                        onclick="confirmClearWishlist()">
                                        <i class="fas fa-trash-alt me-2"></i> {{ __('Clear All') }}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-gradient-rose ms-2"
                                        onclick="confirmAddAllToCart()">
                                        <i class="fas fa-cart-plus me-2"></i> {{ __('Add All to Cart') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $wishlistItems = auth()->check()
                    ? auth()
                        ->user()
                        ->wishlistItems()
                        ->with(['product.images', 'product.variants', 'product.category'])
                        ->get()
                    : collect([]);
            @endphp

            @if ($wishlistItems->count() > 0)
                <!-- Masonry Grid Layout -->
                <div class="row g-4" id="wishlist-container">
                    @foreach ($wishlistItems as $wishlistItem)
                        @php
                            $product = $wishlistItem->product;
                            $firstImage = $product->images->first()->url ?? null;
                            $firstVariant = $product->variants->first();
                            $discount =
                                $firstVariant && $firstVariant->compare_at_price
                                    ? round(
                                        (($firstVariant->compare_at_price - $firstVariant->price) /
                                            $firstVariant->compare_at_price) *
                                            100,
                                    )
                                    : 0;
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4 wishlist-item"
                            data-price="{{ $product->price }}"
                            data-name="{{ $product->name[app()->getLocale()] ?? $product->name['en'] }}"
                            data-added="{{ $wishlistItem->created_at->timestamp }}"
                            data-product-id="{{ $product->id }}">

                            <div class="card border-0 shadow-sm overflow-hidden rounded-4"
                                style="height: 420px; max-height: 420px; background: white; display: flex; flex-direction: column;">

                                <!-- Product Image - Fixed 50% height -->
                                <div class="position-relative" style="height: 210px; flex-shrink: 0; overflow: hidden;">
                                    @if ($firstImage)
                                        <img src="{{ $firstImage }}" class="w-100 h-100 object-fit-cover"
                                            alt="{{ $product->name[app()->getLocale()] ?? $product->name['en'] }}"
                                            loading="lazy" style="transition: transform 0.6s ease;"
                                            onmouseover="this.style.transform='scale(1.08)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                                            style="background: linear-gradient(to bottom right, #f3f4f6, #e5e7eb);">
                                            <i class="fas fa-gem" style="font-size: 2.5rem; color: #d1d5db;"></i>
                                        </div>
                                    @endif

                                    <!-- Discount Badge -->
                                    @if ($product->sale_price && $product->sale_price < $product->price)
                                        @php
                                            $discountPercentage = round(
                                                (($product->price - $product->sale_price) / $product->price) * 100,
                                            );
                                        @endphp
                                        <div style="position: absolute; top: 10px; left: 10px;">
                                            <span
                                                style="background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%); color: white; padding: 4px 8px; border-radius: 50px; font-size: 0.7rem; font-weight: 500; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                                -{{ $discountPercentage }}%
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Remove Button -->
                                    <div style="position: absolute; top: 10px; right: 10px; z-index: 3000;">
                                        <button onclick="confirmRemoveFromWishlist({{ $product->id }})" 
                                                data-bs-toggle="tooltip"
                                                title="{{ __('Remove from wishlist') }}"
                                                style="width: 32px; height: 32px; background: rgba(255, 255, 255, 0.95); border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; z-index: 3000;"
                                                onmouseover="this.style.background='white';this.style.transform='scale(1.1)';this.style.boxShadow='0 3px 10px rgba(0, 0, 0, 0.15)'"
                                                onmouseout="this.style.background='rgba(255, 255, 255, 0.95)';this.style.transform='scale(1)';this.style.boxShadow='none'">
                                            <i class="fas fa-times" style="color: #ef4444; font-size: 0.8rem;"></i>
                                        </button>
                                    </div>

                                    <!-- Quick View Overlay -->
                                    <div style="position: absolute; z-index:2000; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.3s ease;"
                                        onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            style="background: white; color: #333; padding: 8px 16px; border-radius: 50px; font-size: 0.85rem; text-decoration: none; transition: transform 0.3s ease;"
                                            onmouseover="this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.transform='scale(1)'">
                                            <i class="fas fa-eye me-1"></i> {{ __('Quick View') }}
                                        </a>
                                    </div>
                                </div>

                                <!-- Product Content - Remaining 50% with proper flex distribution -->
                                <div
                                    style="flex: 1; display: flex; flex-direction: column; padding: 16px; overflow: hidden; justify-content: space-evenly;">

                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <!-- Category Badge -->
                                        @if ($product->category)
                                            <div>
                                                <span
                                                    style="background-color: #fffbeb; color: #b45309; border: 1px solid #fef3c7; padding: 4px 8px; border-radius: 50px; font-size: 0.65rem; font-weight: 500; display: inline-block;">
                                                    {{ $product->category->name[app()->getLocale()] ?? $product->category->name['en'] }}
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Product Title -->
                                        <h6
                                            style="color: #1f2937; font-size: 0.9rem; font-weight: 600; line-height: 1.3;  
                display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; text-transform:capitalize;">
                                            {{ $product->name[app()->getLocale()] ?? $product->name['en'] }}
                                        </h6>
                                    </div>
                                    <div style="display: flex; justify-content:space-between; align-items:cennter;">
                                        <!-- Price Section -->
                                        <div style="margin-top: auto; margin-bottom: 8px;">
                                            @if ($product->sale_price && $product->sale_price < $product->price)
                                                <div style="display: flex; align-items: baseline; gap: 4px;">
                                                    <span style="font-size: 1rem; font-weight: 700; color: #e11d48;">
                                                        {{ number_format($product->sale_price, 2) }} <small
                                                            style="font-weight: 400; font-size: 0.75rem;">{{ __('MAD') }}</small>
                                                    </span>
                                                    <span
                                                        style="color: #9ca3af; text-decoration: line-through; font-size: 0.8rem;">
                                                        {{ number_format($product->price, 2) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span style="font-size: 1rem; font-weight: 700; color: #1f2937;">
                                                    {{ number_format($product->price, 2) }} <small
                                                        style="font-weight: 400; font-size: 0.75rem;">{{ __('MAD') }}</small>
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Stock Status -->
                                        <div style="margin-bottom: 12px;">
                                            <span
                                                style="background-color: #ecfdf5; color: #059669; border: 1px solid #d1fae5; padding: 4px 8px; border-radius: 50px; font-size: 0.7rem; font-weight: 500; display: inline-flex; align-items: center;">
                                                <i class="fas fa-check-circle me-1" style="font-size: 0.65rem;"></i>
                                                {{ __('In Stock') }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Action Buttons -->
                                    <div style="display: flex; gap: 8px; ">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                            style="flex: 1; font-size: 0.8rem; padding: 8px 12px; border-radius: 8px; border: 1px solid #d1d5db; color: #4b5563; background: transparent; transition: all 0.3s ease; text-decoration: none; display: flex; align-items: center; justify-content: center;"
                                            onmouseover="this.style.borderColor='#9ca3af';this.style.backgroundColor='rgba(107, 114, 128, 0.05)';this.style.color='#374151'"
                                            onmouseout="this.style.borderColor='#d1d5db';this.style.backgroundColor='transparent';this.style.color='#4b5563'">
                                            <i class="fas fa-info-circle me-1" style="font-size: 0.75rem;"></i>
                                            <span>{{ __('Details') }}</span>
                                        </a>
                                        <button onclick="addToCart({{ $product->id }})"
                                            style="flex: 1; font-size: 0.8rem; padding: 8px 12px; border-radius: 8px; border: none; color: white; background: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%); font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;"
                                            onmouseover="this.style.background='linear-gradient(135deg, #e11d48 0%, #db2777 100%)';this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 15px rgba(244, 63, 94, 0.25)'"
                                            onmouseout="this.style.background='linear-gradient(135deg, #f43f5e 0%, #ec4899 100%)';this.style.transform='translateY(0)';this.style.boxShadow='none'">
                                            <i class="fas fa-shopping-cart me-1" style="font-size: 0.75rem;"></i>
                                            <span>{{ __('Add') }}</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Added Date -->
                                <div style="border-top: 1px solid #f0f0f0; padding: 12px 16px; flex-shrink: 0;">
                                    <div style="color: #6b7280; font-size: 0.7rem; display: flex; align-items: center;">
                                        <i class="far fa-clock me-1" style="font-size: 0.65rem;"></i>
                                        {{ __('Added') }} {{ $wishlistItem->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Wishlist Summary -->
                <div class="row mt-8">
                    <div class="container">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-3">{{ __('Wishlist Summary') }}</h6>
                                <div class="row">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="text-center p-3 bg-rose-50 rounded-3">
                                            <div class="fs-4 fw-bold text-rose-600">{{ $wishlistItems->count() }}</div>
                                            <div class="text-gray-600 small">{{ __('Total Items') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        @php
                                            $totalValue = $wishlistItems->sum(function ($item) {
                                                return $item->product->variants->first()->price ?? 0;
                                            });
                                        @endphp
                                        <div class="text-center p-3 bg-blue-50 rounded-3">
                                            <div class="fs-4 fw-bold text-blue-600">
                                                {{ number_format($totalValue, 2) }} <small
                                                    class="fs-6">{{ __('MAD') }}</small>
                                            </div>
                                            <div class="text-gray-600 small">{{ __('Total Value') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="text-center p-3 bg-emerald-50 rounded-3">
                                            <div class="fs-4 fw-bold text-emerald-600">{{ $wishlistItems->count() }}</div>
                                            <div class="text-gray-600 small">{{ __('In Stock') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="text-center p-3 bg-amber-50 rounded-3">
                                            <div class="fs-4 fw-bold text-amber-600">
                                                {{ $wishlistItems->filter(fn($item) => $item->product->sale_price && $item->product->sale_price < $item->product->price)->count() }}
                                            </div>
                                            <div class="text-gray-600 small">{{ __('On Sale') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="text-center py-16">
                    <div class="mb-6">
                        <div
                            class="heart-pulse d-inline-flex align-items-center justify-content-center rounded-circle p-6 mb-4">
                            <i class="fas fa-heart text-rose-500" style="font-size: 5rem;"></i>
                        </div>
                        <div class="heart-pulse-ring"></div>
                    </div>

                    <h2 class="fs-2 fw-black text-gray-900 mb-3">{{ __('Your wishlist is waiting for love') }}</h2>
                    <p class="text-gray-600 fs-5 mb-6 mx-auto" style="max-width: 500px;">
                        {{ __('Save your favorite products here to revisit them later. Start exploring our curated collection.') }}
                    </p>

                    <!-- Search Bar -->
                    <div class="mb-6 mx-auto" style="max-width: 400px;">
                        <div class="input-group shadow-sm rounded-pill overflow-hidden">
                            <span class="input-group-text bg-white border-0">
                                <i class="fas fa-search text-gray-400"></i>
                            </span>
                            <input type="text" class="form-control border-0"
                                placeholder="{{ __('Search for products...') }}" id="wishlist-search">
                            <button class="btn btn-gradient-rose px-4">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>

                    <!-- Category Quick Links -->
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">{{ __('Popular Categories') }}</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            @foreach (['jewelry', 'home-decor', 'clothing', 'art'] as $category)
                                <a href="{{ route('products.index', ['category' => $category]) }}"
                                    class="badge bg-light text-gray-700 px-4 py-2 rounded-pill hover-lift">
                                    <i
                                        class="fas fa-{{ $category === 'jewelry' ? 'gem' : ($category === 'home-decor' ? 'home' : ($category === 'clothing' ? 'tshirt' : 'palette')) }} me-2"></i>
                                    {{ ucfirst($category) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('products.index') }}"
                            class="btn btn-gradient-rose btn-lg px-5 py-3 rounded-pill hover-scale">
                            <i class="fas fa-sparkles me-2"></i> {{ __('Browse Featured Products') }}
                        </a>
                        <a href="{{ route('services.index') }}"
                            class="btn btn-outline-gray-700 btn-lg px-5 py-3 rounded-pill hover-scale">
                            <i class="fas fa-magic me-2"></i> {{ __('Explore Services') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Dialog Container -->
    <div id="dialog-container"></div>

    @push('styles')
        <style>
            /* Custom Color Variables */
            .bg-rose-50 {
                background-color: #fff1f2;
            }

            .bg-rose-100 {
                background-color: #ffe4e6;
            }

            .text-rose-500 {
                color: #f43f5e;
            }

            .text-rose-600 {
                color: #e11d48;
            }

            .bg-emerald-50 {
                background-color: #ecfdf5;
            }

            .bg-emerald-100 {
                background-color: #d1fae5;
            }

            .text-emerald-600 {
                color: #059669;
            }

            .bg-amber-50 {
                background-color: #fffbeb;
            }

            .bg-amber-100 {
                background-color: #fef3c7;
            }

            .text-amber-700 {
                color: #b45309;
            }

            .bg-blue-50 {
                background-color: #eff6ff;
            }

            .text-blue-600 {
                color: #2563eb;
            }

            /* Dialog Alert Styles */
            .dialog-modal .modal-content {
                border: none;
                border-radius: 16px;
                overflow: hidden;
                animation: dialogSlideIn 0.3s ease-out;
            }

            @keyframes dialogSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(-50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .dialog-icon {
                font-size: 4rem;
                margin-bottom: 1.5rem;
            }

            .icon-wrapper {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .icon-success {
                background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
                color: #10b981;
            }

            .icon-warning {
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
                color: #f59e0b;
            }

            .icon-danger {
                background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
                color: #ef4444;
            }

            .icon-info {
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.05));
                color: #3b82f6;
            }

            .icon-confirm {
                background: linear-gradient(135deg, rgba(168, 85, 247, 0.1), rgba(168, 85, 247, 0.05));
                color: #8b5cf6;
            }

            .dialog-message {
                color: #6b7280;
                font-size: 1.1rem;
                line-height: 1.6;
                margin-bottom: 1.5rem;
            }

            .dialog-confirm-btn {
                background: linear-gradient(135deg, #f43f5e, #ec4899);
                border: none;
                color: white;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .dialog-confirm-btn:hover {
                background: linear-gradient(135deg, #e11d48, #db2777);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(244, 63, 94, 0.3);
                color: white;
            }

            .dialog-danger-btn {
                background: linear-gradient(135deg, #ef4444, #dc2626);
                border: none;
                color: white;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .dialog-danger-btn:hover {
                background: linear-gradient(135deg, #dc2626, #b91c1c);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
                color: white;
            }

            /* Toast Styles */
            .toast-alert {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                transform: translateX(100%);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 9999;
                min-width: 300px;
            }

            .toast-alert.show {
                transform: translateX(0);
                opacity: 1;
            }

            .toast-success {
                border-left: 4px solid #10b981;
            }

            .toast-error {
                border-left: 4px solid #ef4444;
            }

            .toast-warning {
                border-left: 4px solid #f59e0b;
            }

            .toast-info {
                border-left: 4px solid #3b82f6;
            }

            .toast-content {
                display: flex;
                align-items: center;
                font-weight: 500;
            }

            /* Fade out animation */
            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(-20px); }
            }

            /* Bounce animation for cart counter */
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
                40% {transform: translateY(-10px);}
                60% {transform: translateY(-5px);}
            }

            .bounce {
                animation: bounce 0.5s;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Dialog Service Functions
            class DialogService {
                constructor() {
                    this.dialogCounter = 0;
                    this.initContainer();
                }

                initContainer() {
                    if (!document.getElementById('dialog-container')) {
                        const container = document.createElement('div');
                        container.id = 'dialog-container';
                        document.body.appendChild(container);
                    }
                }

                showDialog(config) {
                    const id = 'dialog-' + ++this.dialogCounter;
                    const dialog = this.createDialogHTML(id, config);
                    
                    document.getElementById('dialog-container').innerHTML = dialog;
                    
                    const modalEl = document.getElementById(id);
                    const modal = new bootstrap.Modal(modalEl);
                    
                    // Handle confirm button click
                    const confirmBtn = modalEl.querySelector('.dialog-confirm-btn');
                    if (confirmBtn && config.onConfirm) {
                        confirmBtn.addEventListener('click', function(e) {
                            if (typeof config.onConfirm === 'function') {
                                config.onConfirm(e);
                            }
                            if (config.callback) {
                                config.callback(true);
                            }
                        });
                    }
                    
                    // Handle cancel button click
                    const cancelBtn = modalEl.querySelector('[data-bs-dismiss="modal"]');
                    if (cancelBtn && config.onCancel) {
                        cancelBtn.addEventListener('click', function(e) {
                            if (typeof config.onCancel === 'function') {
                                config.onCancel(e);
                            }
                            if (config.callback) {
                                config.callback(false);
                            }
                        });
                    }
                    
                    // Auto close timer
                    if (config.timer) {
                        setTimeout(() => {
                            modal.hide();
                            this.removeDialog(id);
                            if (config.callback) {
                                config.callback(null);
                            }
                        }, config.timer);
                    }
                    
                    // Remove from DOM after hiding
                    modalEl.addEventListener('hidden.bs.modal', () => {
                        this.removeDialog(id);
                    });
                    
                    modal.show();
                    return modal;
                }

                createDialogHTML(id, config) {
                    const iconMap = {
                        success: '<i class="fas fa-check-circle"></i>',
                        error: '<i class="fas fa-times-circle"></i>',
                        warning: '<i class="fas fa-exclamation-triangle"></i>',
                        info: '<i class="fas fa-info-circle"></i>',
                        confirm: '<i class="fas fa-question-circle"></i>',
                        danger: '<i class="fas fa-trash-alt"></i>'
                    };

                    const icon = iconMap[config.type] || iconMap.info;
                    const btnClass = config.type === 'danger' ? 'dialog-danger-btn' : 'dialog-confirm-btn';
                    
                    return `
                        <div class="modal fade dialog-modal" id="${id}" tabindex="-1" style="z-index:4000;" 
                             aria-labelledby="${id}-label" aria-hidden="true"
                             ${config.staticBackdrop ? 'data-bs-backdrop="static" data-bs-keyboard="false"' : ''}>
                            <div class="modal-dialog modal-dialog-centered modal-${config.size || 'md'}">
                                <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                                    <div class="modal-header border-0 pb-0">
                                        ${config.showCloseButton !== false ? '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' : ''}
                                    </div>
                                    <div class="modal-body px-5 pt-0 pb-4 text-center">
                                        <div class="dialog-icon">
                                            <div class="icon-wrapper icon-${config.type} mx-auto">
                                                ${icon}
                                            </div>
                                        </div>
                                        ${config.title ? `<h5 class="modal-title fw-bold mb-3" id="${id}-label">${config.title}</h5>` : ''}
                                        <div class="dialog-message">
                                            ${config.message}
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0 px-5 pb-5">
                                        ${config.showCancel ? `
                                            <button type="button" 
                                                    class="btn btn-outline-secondary btn-lg px-5 rounded-pill"
                                                    data-bs-dismiss="modal">
                                                ${config.cancelText || 'Cancel'}
                                            </button>
                                        ` : ''}
                                        <button type="button" 
                                                class="btn btn-lg px-5 rounded-pill ${btnClass}"
                                                data-bs-dismiss="modal">
                                            ${config.confirmText || 'OK'}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                removeDialog(id) {
                    const dialog = document.getElementById(id);
                    if (dialog) {
                        dialog.remove();
                    }
                }

                // Quick Methods
                success(message, title = 'Success!', callback = null) {
                    return this.showDialog({
                        title,
                        message,
                        type: 'success',
                        confirmText: 'OK',
                        timer: 3000,
                        callback
                    });
                }

                error(message, title = 'Error!', callback = null) {
                    return this.showDialog({
                        title,
                        message,
                        type: 'error',
                        confirmText: 'OK',
                        callback
                    });
                }

                warning(message, title = 'Warning!', callback = null) {
                    return this.showDialog({
                        title,
                        message,
                        type: 'warning',
                        confirmText: 'OK',
                        callback
                    });
                }

                info(message, title = 'Information', callback = null) {
                    return this.showDialog({
                        title,
                        message,
                        type: 'info',
                        confirmText: 'OK',
                        timer: 2500,
                        callback
                    });
                }

                confirm(message, title = 'Confirm Action', onConfirm, onCancel = null) {
                    return this.showDialog({
                        title,
                        message,
                        type: 'confirm',
                        showCancel: true,
                        confirmText: 'Yes',
                        cancelText: 'No',
                        onConfirm,
                        onCancel
                    });
                }

                deleteConfirm(message = 'Are you sure you want to delete this item?', onConfirm) {
                    return this.showDialog({
                        title: 'Confirm Delete',
                        message,
                        type: 'danger',
                        showCancel: true,
                        confirmText: 'Delete',
                        cancelText: 'Cancel',
                        onConfirm
                    });
                }
            }

            // Create global dialog instance
            const Dialog = new DialogService();

            // Wishlist Functions with Dialog Confirmations
            function confirmRemoveFromWishlist(productId) {
                Dialog.deleteConfirm(
                    '{{ __("Are you sure you want to remove this item from your wishlist?") }}',
                    function() {
                        removeFromWishlist(productId);
                    }
                );
            }

            function confirmClearWishlist() {
                Dialog.deleteConfirm(
                    '{{ __("Are you sure you want to clear your entire wishlist? This action cannot be undone.") }}',
                    function() {
                        clearWishlist();
                    }
                );
            }

            function confirmAddAllToCart() {
                Dialog.confirm(
                    '{{ __("Add all items from your wishlist to cart?") }}',
                    'Add All to Cart',
                    function() {
                        addAllToCart();
                    }
                );
            }

            // Original Functions (updated to show success dialogs)
            function removeFromWishlist(productId) {
                const item = event ? event.target.closest('.wishlist-item') : document.querySelector(`[data-product-id="${productId}"]`);
                
                if (!item) return;
                
                // Add removal animation
                item.style.animation = 'fadeOut 0.3s ease forwards';

                fetch(`/wishlist/remove/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            setTimeout(() => {
                                item.remove();
                                updateWishlistStats();
                                Dialog.success('{{ __("Item removed from wishlist") }}');
                            }, 300);
                        } else {
                            item.style.animation = '';
                            Dialog.error(data.message || '{{ __("Failed to remove item") }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        item.style.animation = '';
                        Dialog.error('{{ __("An error occurred. Please try again.") }}');
                    });
            }

            function addToCart(productId) {
                const button = event.target.closest('button');
                const originalHtml = button.innerHTML;

                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> {{ __("Adding...") }}';
                button.disabled = true;

                fetch(`/cart/add-product/${productId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            button.innerHTML = '<i class="fas fa-check me-2"></i> {{ __("Added!") }}';
                            button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                            
                            // Update cart counter if exists
                            updateCartCounter(data.cart_count || 1);

                            // Reset button after 2 seconds
                            setTimeout(() => {
                                button.innerHTML = originalHtml;
                                button.style.background = '';
                                button.disabled = false;
                            }, 2000);

                            Dialog.success('{{ __("Item added to cart successfully!") }}');
                        } else {
                            button.innerHTML = originalHtml;
                            button.disabled = false;
                            Dialog.error(data.message || '{{ __("Failed to add item to cart") }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.innerHTML = originalHtml;
                        button.disabled = false;
                        Dialog.error('{{ __("An error occurred. Please try again.") }}');
                    });
            }

            function addAllToCart() {
                const productIds = Array.from(document.querySelectorAll('.wishlist-item'))
                    .map(item => item.getAttribute('data-product-id'))
                    .filter(id => id);

                if (productIds.length === 0) {
                    Dialog.info('{{ __("Your wishlist is empty") }}');
                    return;
                }

                // Show progress dialog
                const progressDialog = Dialog.showDialog({
                    title: '{{ __("Adding Items to Cart") }}',
                    message: `<div class="text-center">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mb-0">Adding items to cart...</p>
                                <div class="progress mt-3" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" id="cart-progress"></div>
                                </div>
                              </div>`,
                    type: 'info',
                    showCancel: false,
                    showCloseButton: false,
                    staticBackdrop: true,
                    size: 'sm'
                });

                let completed = 0;
                const total = productIds.length;
                
                // Simulate adding items (replace with actual API calls)
                productIds.forEach((id, index) => {
                    setTimeout(() => {
                        // Simulate API call
                        fetch(`/cart/add-product/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ quantity: 1 })
                        }).finally(() => {
                            completed++;
                            const progress = (completed / total) * 100;
                            const progressBar = document.getElementById('cart-progress');
                            if (progressBar) {
                                progressBar.style.width = `${progress}%`;
                            }
                            
                            if (completed === total) {
                                setTimeout(() => {
                                    progressDialog.hide();
                                    Dialog.success(`{{ __("Successfully added") }} ${total} {{ __("items to cart") }}`);
                                    updateCartCounter(total); // Update cart count
                                }, 500);
                            }
                        });
                    }, index * 300); // Stagger requests
                });
            }

            function clearWishlist() {
                fetch(`/wishlist/clear`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('wishlist-container').innerHTML = '';
                            updateWishlistStats();
                            Dialog.success('{{ __("Wishlist cleared successfully") }}');
                            // Show empty state after delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            Dialog.error(data.message || '{{ __("Failed to clear wishlist") }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Dialog.error('{{ __("An error occurred. Please try again.") }}');
                    });
            }

            // Helper Functions
            function updateWishlistStats() {
                const itemCount = document.querySelectorAll('.wishlist-item').length;
                // Update any wishlist counters on the page
                const counters = document.querySelectorAll('.wishlist-counter');
                counters.forEach(counter => {
                    counter.textContent = itemCount;
                });
            }

            function updateCartCounter(count) {
                const cartCounter = document.querySelector('.cart-counter');
                if (cartCounter) {
                    cartCounter.textContent = count;
                    cartCounter.classList.add('bounce');
                    setTimeout(() => cartCounter.classList.remove('bounce'), 500);
                }
            }

            // Initialize tooltips and other DOM elements
            document.addEventListener('DOMContentLoaded', function() {
                // Tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                // Set animation delays for items
                document.querySelectorAll('.wishlist-item').forEach((item, index) => {
                    item.style.setProperty('--item-index', index);
                });

                // Initialize search functionality
                const searchInput = document.getElementById('wishlist-search');
                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const searchTerm = e.target.value.toLowerCase();
                        document.querySelectorAll('.wishlist-item').forEach(item => {
                            const productName = item.getAttribute('data-name').toLowerCase();
                            item.style.display = productName.includes(searchTerm) ? 'block' : 'none';
                        });
                    });
                }
            });

            // Sort functionality (unchanged)
            function sortWishlist(sortType) {
                const container = document.getElementById('wishlist-container');
                const items = Array.from(container.querySelectorAll('.wishlist-item'));

                items.sort((a, b) => {
                    switch (sortType) {
                        case 'price-low':
                            return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                        case 'price-high':
                            return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                        case 'name':
                            return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                        case 'recent':
                        default:
                            return parseFloat(b.getAttribute('data-added')) - parseFloat(a.getAttribute('data-added'));
                    }
                });

                // Reorder items with animation
                items.forEach((item, index) => {
                    container.appendChild(item);
                    item.style.animation = 'none';
                    item.offsetHeight; // Trigger reflow
                    item.style.animation = `fadeInUp 0.5s ease forwards ${index * 0.1}s`;
                });

                Dialog.success(`Sorted by ${document.querySelector(`[value="${sortType}"]`).textContent}`);
            }
        </script>
    @endpush
@endsection