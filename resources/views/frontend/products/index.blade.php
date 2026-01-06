@push('styles')
<style>
/* Enhanced Pagination Styles */
.pagination-modern {
    gap: 8px;
    margin: 0;
}

.pagination-modern .page-item {
    margin: 0;
}

.pagination-modern .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border: 2px solid #e9e5e0;
    background: white;
    color: #5d5348;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.pagination-modern .page-link.rounded-circle {
    border-radius: 50% !important;
}

.pagination-modern .page-link:hover {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-color: #fbbf24;
    color: #92400e;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.15);
}

.pagination-modern .page-link:active {
    transform: translateY(0);
}

/* Current page styling */
.pagination-modern .page-item.active .page-link {
    background: linear-gradient(135deg, #9C6644, #7D4F35);
    border-color: #9C6644;
    color: white;
    box-shadow: 0 6px 20px rgba(156, 102, 68, 0.25);
    position: relative;
    transform: scale(1.05);
}

.pagination-modern .page-item.active .page-link::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 20px;
    height: 3px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 2px;
}

/* Disabled state */
.pagination-modern .page-item.disabled .page-link {
    background: #f9fafb;
    border-color: #f3f4f6;
    color: #d1d5db;
    cursor: not-allowed;
    opacity: 0.7;
    transform: none;
    box-shadow: none;
}

.pagination-modern .page-item.disabled .page-link:hover {
    background: #f9fafb;
    border-color: #f3f4f6;
    color: #d1d5db;
    transform: none;
    box-shadow: none;
}

/* SVG icons styling */
.pagination-modern .page-link svg {
    width: 20px;
    height: 20px;
}

.pagination-modern .page-link:hover svg {
    stroke: #92400e;
}

.pagination-modern .page-item.active .page-link svg {
    stroke: white;
}

/* Mobile pagination */
@media (max-width: 767.98px) {
    .pagination-modern .page-link {
        width: 42px;
        height: 42px;
        font-size: 0.875rem;
    }
    
    .btn-terracotta, .btn-outline-terracotta {
        min-width: 100px;
    }
}

/* Animation for page changes */
@keyframes pageTransition {
    0% {
        opacity: 0.5;
        transform: translateY(10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.pagination-modern .page-link {
    animation: pageTransition 0.3s ease-out;
}

/* Hover effect with subtle scale */
.pagination-modern .page-link:hover {
    animation: hoverBounce 0.3s ease;
}

@keyframes hoverBounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-4px);
    }
}

/* Current page pulse effect */
@keyframes pulseGlow {
    0%, 100% {
        box-shadow: 0 6px 20px rgba(156, 102, 68, 0.25);
    }
    50% {
        box-shadow: 0 6px 25px rgba(156, 102, 68, 0.4);
    }
}

.pagination-modern .page-item.active .page-link {
    animation: pulseGlow 2s infinite;
}

/* Focus state for accessibility */
.pagination-modern .page-link:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(156, 102, 68, 0.3);
}

/* Per page selector styling */
#per_page {
    transition: all 0.2s ease;
    cursor: pointer;
}

#per_page:hover {
    background: #fef3c7 !important;
    box-shadow: 0 2px 8px rgba(156, 102, 68, 0.1);
}

#per_page:focus {
    box-shadow: 0 0 0 2px rgba(156, 102, 68, 0.2) !important;
    border-color: #9C6644 !important;
}

/* Responsive adjustments */
@media (max-width: 575.98px) {
    .pagination-info {
        text-align: center !important;
        margin-bottom: 1rem;
    }
    
    #per_page {
        width: 60px;
    }
}

/* RTL Support */
[dir="rtl"] .pagination-modern .page-link svg {
    transform: rotate(180deg);
}

[dir="rtl"] .pagination-modern .page-item:first-child .page-link {
    border-radius: 0.375rem 0 0 0.375rem;
}

[dir="rtl"] .pagination-modern .page-item:last-child .page-link {
    border-radius: 0 0.375rem 0.375rem 0;
}
</style>
@endpush@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
    $locale = app()->getLocale();
@endphp

@extends('layouts.frontend')

@section('title', __('Products') . ' - ' . config('app.name'))

@section('content')
<!-- Breadcrumb Section -->
<section class="bg-white border-bottom py-3">
    <div class="container" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ LaravelLocalization::localizeUrl(route('home')) }}" class="text-decoration-none text-stone">
                        {{ __('Home') }}
                    </a>
                </li>
                <li class="breadcrumb-item active text-charcoal" aria-current="page">
                    {{ __('Products') }}
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Products Section -->
<section class="py-5 bg-light">
    <div class="container" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <!-- Page Header -->
        <div class="mb-4 animate-fade-in-up">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                <div>
                    <h1 class="display-5 fw-bold text-charcoal mb-2" style="font-family: var(--bs-font-serif);">{{ __('All Products') }}</h1>
                    <p class="text-stone mb-0">{{ __('Discover our curated collection') }}</p>
                </div>
                <button onclick="toggleFilters()" 
                        id="filterToggle"
                        class="btn btn-outline-dark rounded-pill px-4 d-lg-none mt-3 mt-md-0">
                    <svg width="20" height="20" class="{{ $isRtl ? 'ms-2' : 'me-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span id="filterText">{{ __('Filters') }}</span>
                </button>
            </div>
            <div class="bg-terracotta rounded-pill" style="width: 96px; height: 4px;"></div>
        </div>

        <div class="row g-4">
            <!-- Filters Sidebar -->
            <aside class="col-lg-3" id="filterSidebar">
                <div class="card border-0 shadow-sm position-sticky animate-fade-in-up animation-delay-100" style="top: 100px; max-height: calc(100vh - 120px); overflow-y: auto;">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold text-charcoal mb-4 pb-3 border-bottom">
                            <svg width="20" height="20" class="{{ $isRtl ? 'ms-2' : 'me-2' }} text-terracotta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            {{ __('Filters') }}
                        </h2>
                        
                        <form action="{{ LaravelLocalization::localizeUrl(route('products.index')) }}" method="GET">
                            <!-- Search -->
                            <div class="mb-4">
                                <label for="search" class="form-label small fw-bold text-charcoal">
                                    {{ __('Search') }}
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <svg width="18" height="18" class="text-stone" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <input type="text" 
                                           name="search" 
                                           id="search" 
                                           value="{{ request('search') }}"
                                           placeholder="{{ __('Search products...') }}"
                                           class="form-control border-start-0 ps-0">
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <h3 class="small fw-bold text-charcoal mb-3">{{ __('Categories') }}</h3>
                                <div class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($categories as $category)
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               name="categories[]" 
                                               value="{{ $category->id }}"
                                               id="cat-{{ $category->id }}"
                                               {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                               class="form-check-input">
                                        <label class="form-check-label small d-flex justify-content-between w-100" for="cat-{{ $category->id }}">
                                            <span>{{ $category->name[$locale] ?? $category->name['en'] }}</span>
                                            <span class="badge bg-light text-stone rounded-pill">{{ $category->products_count }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <h3 class="small fw-bold text-charcoal mb-3">{{ __('Price Range') }}</h3>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" 
                                               name="price_min" 
                                               value="{{ request('price_min') }}"
                                               placeholder="{{ __('Min') }}"
                                               min="0"
                                               class="form-control form-control-sm">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" 
                                               name="price_max" 
                                               value="{{ request('price_max') }}"
                                               placeholder="{{ __('Max') }}"
                                               min="0"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Availability -->
                            <div class="mb-4 pb-4 border-bottom">
                                <h3 class="small fw-bold text-charcoal mb-3">{{ __('Availability') }}</h3>
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           name="in_stock" 
                                           value="1"
                                           id="inStock"
                                           {{ request('in_stock') ? 'checked' : '' }}
                                           class="form-check-input" 
                                           role="switch">
                                    <label class="form-check-label small" for="inStock">
                                        {{ __('In Stock Only') }}
                                    </label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-terracotta rounded-pill fw-bold">
                                    {{ __('Apply Filters') }}
                                </button>
                                <a href="{{ LaravelLocalization::localizeUrl(route('products.index')) }}" 
                                   class="btn btn-outline-secondary rounded-pill">
                                    {{ __('Reset All') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="card border-0 shadow-sm mb-4 animate-fade-in-up animation-delay-200">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <p class="small text-stone mb-0">
                                    {{ __('Showing :from-:to of :total products', [
                                        'from' => $products->firstItem() ?? 0,
                                        'to' => $products->lastItem() ?? 0,
                                        'total' => $products->total()
                                    ]) }}
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-md-end gap-2">
                                    <label for="sort" class="small text-charcoal fw-medium mb-0 text-nowrap">{{ __('Sort by:') }}</label>
                                    <select name="sort" 
                                            id="sort" 
                                            onchange="window.location.href = this.value"
                                            class="form-select form-select-sm" style="max-width: 200px;">
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                            {{ __('Newest') }}
                                        </option>
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                            {{ __('Price: Low to High') }}
                                        </option>
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                            {{ __('Price: High to Low') }}
                                        </option>
                                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}" {{ request('sort') === 'name' ? 'selected' : '' }}>
                                            {{ __('Name: A-Z') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                @if($products->isEmpty())
                    <!-- Empty State -->
                    <div class="card border-0 shadow-sm text-center py-5 animate-fade-in-up animation-delay-300">
                        <div class="card-body p-5">
                            <svg class="text-stone mb-4" width="96" height="96" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="h4 fw-bold text-charcoal mb-3">{{ __('No products found') }}</h3>
                            <p class="text-stone mb-4">{{ __('Try adjusting your filters or search terms') }}</p>
                            <a href="{{ LaravelLocalization::localizeUrl(route('products.index')) }}" 
                               class="btn btn-terracotta btn-lg rounded-pill px-5">
                                {{ __('Clear Filters') }}
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Products -->
                    <div class="row g-4 mb-4 p-3 rounded-3 animate-fade-in-up animation-delay-300">
                        @foreach($products as $product)
                        <div class="col-md-6 col-xl-4 animate-fade-in-up animation-delay-{{ ($loop->index % 6) * 100 + 300 }}">
                            <div class="card border-0 shadow-sm card-hover h-100">
                                <!-- Product Image -->
                                <div class="position-relative overflow-hidden rounded-top" style="padding-top: 75%;">
                                    <a href="{{ $product->slug ? LaravelLocalization::localizeUrl(route('products.show', $product->slug)) : '#' }}" 
                                       class="d-block position-absolute top-0 start-0 w-100 h-100">
                                        @if($product->images && $product->images->isNotEmpty())
                                            <img src="{{ $product->images->first()->url }}" 
                                                 alt="{{ $product->name[$locale] ?? $product->name['en'] }}" 
                                                 class="w-100 h-100 object-fit-cover transition-transform duration-500 hover:scale-105">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-parchment">
                                                <svg width="60" height="60" class="text-ochre opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <!-- Badges -->
                                    @if($product->variants && $product->variants->first())
                                        @php $variantStock = $product->variants->first()->quantity ?? $product->variants->first()->stock ?? 0; @endphp
                                        @if($variantStock <= 0)
                                            <div class="position-absolute top-2 {{ $isRtl ? 'start-2' : 'end-2' }}">
                                                <span class="badge bg-danger rounded-pill px-2 py-1 small">
                                                    {{ __('Out of Stock') }}
                                                </span>
                                            </div>
                                        @elseif($product->variants->first()->compare_at_price && $product->variants->first()->compare_at_price > $product->variants->first()->price)
                                            <div class="position-absolute top-2 {{ $isRtl ? 'start-2' : 'end-2' }}">
                                                <span class="badge bg-terracotta rounded-pill px-2 py-1 small">
                                                    -{{ round((($product->variants->first()->compare_at_price - $product->variants->first()->price) / $product->variants->first()->compare_at_price) * 100) }}%
                                                </span>
                                            </div>
                                        @endif
                                    @else
                                        @if($product->stock_quantity <= 0)
                                            <div class="position-absolute top-2 {{ $isRtl ? 'start-2' : 'end-2' }}">
                                                <span class="badge bg-danger rounded-pill px-2 py-1 small">
                                                    {{ __('Out of Stock') }}
                                                </span>
                                            </div>
                                        @elseif($product->sale_price && $product->sale_price < $product->price)
                                            <div class="position-absolute top-2 {{ $isRtl ? 'start-2' : 'end-2' }}">
                                                <span class="badge bg-terracotta rounded-pill px-2 py-1 small">
                                                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <!-- Wishlist Button -->
                                    <button class="position-absolute {{ $isRtl ? 'end-2' : 'start-2' }} top-2 btn btn-white rounded-circle p-1 shadow-sm" 
                                            style="width: 34px; height: 34px;"
                                            onclick="event.preventDefault(); alert('{{ __('Wishlist feature coming soon!') }}');">
                                        <svg width="16" height="16" class="text-terracotta" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Product Info -->
                                <div class="card-body p-3">
                                    <!-- Category and Stock Alert -->
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="small text-terracotta fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                                            {{ $product->category->name[$locale] ?? $product->category->name['en'] }}
                                        </span>
                                        @if($product->variants && $product->variants->first())
                                            @if($variantStock > 0 && $variantStock <= 5)
                                                <span class="badge bg-warning text-dark small px-2 py-1" style="font-size: 0.7rem;">
                                                    {{ __('Only') }} {{ $variantStock }} {{ __('left') }}
                                                </span>
                                            @endif
                                        @else
                                            @if($product->stock_quantity > 0 && $product->stock_quantity <= 5)
                                                <span class="badge bg-warning text-dark small px-2 py-1" style="font-size: 0.7rem;">
                                                    {{ __('Only') }} {{ $product->stock_quantity }} {{ __('left') }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                    
                                    <!-- Product Name -->
                                    <h3 class="h6 fw-bold text-charcoal mb-2" style="font-family: var(--bs-font-serif); line-height: 1.3;">
                                        <a href="{{ $product->slug ? LaravelLocalization::localizeUrl(route('products.show', $product->slug)) : '#' }}" 
                                           class="text-decoration-none text-charcoal stretched-link">
                                            {{ Str::limit($product->name[$locale] ?? $product->name['en'], 60) }}
                                        </a>
                                    </h3>
                                    
                                    <!-- Product Description -->
                                    <p class="small text-stone mb-2" style="font-size: 0.85rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8em;">
                                        {{ Str::limit($product->description[$locale] ?? $product->description['en'], 70) }}
                                    </p>
                                    
                                    <!-- Price and Add to Cart -->
                                    <div class="mt-2 pt-2 border-top">
                                        <!-- Price -->
                                        <div class="mb-2">
                                            @if($product->variants && $product->variants->first())
                                                @if($product->variants->first()->compare_at_price && $product->variants->first()->compare_at_price > $product->variants->first()->price)
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="h5 fw-bold text-terracotta mb-0">
                                                            {{ number_format($product->variants->first()->price, 2) }} {{ __('MAD') }}
                                                        </span>
                                                        <span class="small text-decoration-line-through text-stone" style="font-size: 0.8rem;">
                                                            {{ number_format($product->variants->first()->compare_at_price, 2) }} {{ __('MAD') }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="h5 fw-bold text-charcoal mb-0">
                                                        {{ number_format($product->variants->first()->price, 2) }} {{ __('MAD') }}
                                                    </span>
                                                @endif
                                            @else
                                                @if($product->sale_price && $product->sale_price < $product->price)
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="h5 fw-bold text-terracotta mb-0">
                                                            {{ number_format($product->sale_price, 2) }} {{ __('MAD') }}
                                                        </span>
                                                        <span class="small text-decoration-line-through text-stone" style="font-size: 0.8rem;">
                                                            {{ number_format($product->price, 2) }} {{ __('MAD') }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="h5 fw-bold text-charcoal mb-0">
                                                        {{ number_format($product->price, 2) }} {{ __('MAD') }}
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                        
                                        <!-- Add to Cart Button -->
                                        @if($product->variants && $product->variants->first())
                                            @php $btnVariantStock = $product->variants->first()->quantity ?? $product->variants->first()->stock ?? 0; @endphp
                                            <button onclick="event.stopPropagation(); addToCart({{ $product->id }}, {{ $product->variants->first()->id }})"
                                                    class="btn btn-dark w-100 rounded-pill fw-bold position-relative py-2 {{ $btnVariantStock <= 0 ? 'disabled' : '' }}"
                                                    style="z-index: 2; font-size: 0.9rem;"
                                                    {{ $btnVariantStock <= 0 ? 'disabled' : '' }}>
                                                <svg width="16" height="16" class="{{ $isRtl ? 'ms-1' : 'me-1' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                                {{ $btnVariantStock <= 0 ? __('Out of Stock') : __('Add to Cart') }}
                                            </button>
                                        @else
                                            <button onclick="event.stopPropagation(); addToCart({{ $product->id }}, null)"
                                                    class="btn btn-dark w-100 rounded-pill fw-bold position-relative py-2 {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}"
                                                    style="z-index: 2; font-size: 0.9rem;"
                                                    {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                                <svg width="16" height="16" class="{{ $isRtl ? 'ms-1' : 'me-1' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                                {{ $product->stock_quantity <= 0 ? __('Out of Stock') : __('Add to Cart') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="mt-5 animate-fade-in-up animation-delay-500">
                        <div class="bg-white rounded-3 border border-gray-100 shadow-sm">
                            <div class="p-4 p-md-5">
                                <!-- Pagination Info -->
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom border-gray-100">
                                    <div class="text-center text-md-start">
                                        <p class="text-stone mb-0 fw-medium">
                                            {!! __('Showing <span class="text-terracotta fw-bold">:from</span> - <span class="text-terracotta fw-bold">:to</span> of <span class="text-terracotta fw-bold">:total</span> handcrafted products', [
                                                'from' => $products->firstItem() ?? 0,
                                                'to' => $products->lastItem() ?? 0,
                                                'total' => $products->total()
                                            ]) !!}
                                        </p>
                                    </div>
                                    
                                    <!-- Per Page Selector -->
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-charcoal fw-medium">{{ __('Show:') }}</span>
                                        <select name="per_page" 
                                                id="per_page" 
                                                onchange="updatePerPage(this.value)"
                                                class="form-select form-select-sm border-0 bg-light rounded-pill" 
                                                style="width: 70px;">
                                            <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                                            <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24</option>
                                            <option value="36" {{ request('per_page', 12) == 36 ? 'selected' : '' }}>36</option>
                                            <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>48</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Enhanced Pagination Links -->
                                <nav aria-label="Product navigation" class="d-flex align-items-center justify-content-center">
                                    {{-- Desktop Pagination --}}
                                    <ul class="pagination pagination-modern mb-0 d-none d-md-flex">
                                        {{-- First Page --}}
                                        @if($products->currentPage() > 2)
                                            <li class="page-item">
                                                <a class="page-link rounded-circle" 
                                                   href="{{ $products->url(1) }}" 
                                                   aria-label="{{ __('First page') }}">
                                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                                                    </svg>
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Previous Page --}}
                                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                            <a class="page-link rounded-circle" 
                                               href="{{ $products->previousPageUrl() }}" 
                                               aria-label="{{ __('Previous') }}">
                                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </a>
                                        </li>

                                        {{-- Page Numbers with Dots --}}
                                        @php
                                            $current = $products->currentPage();
                                            $last = $products->lastPage();
                                            $start = max($current - 2, 1);
                                            $end = min($current + 2, $last);
                                            
                                            if($start > 1) {
                                                $start = max($current - 1, 1);
                                                $end = min($current + 1, $last);
                                            }
                                            
                                            if($end == $last && $last > 5) {
                                                $start = max($last - 4, 1);
                                            }
                                        @endphp

                                        {{-- Show first page if not in range --}}
                                        @if($start > 1)
                                            <li class="page-item">
                                                <a class="page-link rounded-circle" href="{{ $products->url(1) }}">1</a>
                                            </li>
                                            @if($start > 2)
                                                <li class="page-item disabled">
                                                    <span class="page-link rounded-circle bg-transparent border-0">...</span>
                                                </li>
                                            @endif
                                        @endif

                                        {{-- Page Links --}}
                                        @for($i = $start; $i <= $end; $i++)
                                            <li class="page-item {{ $i == $current ? 'active' : '' }}">
                                                <a class="page-link rounded-circle {{ $i == $current ? 'current-page' : '' }}" 
                                                   href="{{ $products->url($i) }}">
                                                    {{ $i }}
                                                </a>
                                            </li>
                                        @endfor

                                        {{-- Show last page if not in range --}}
                                        @if($end < $last)
                                            @if($end < $last - 1)
                                                <li class="page-item disabled">
                                                    <span class="page-link rounded-circle bg-transparent border-0">...</span>
                                                </li>
                                            @endif
                                            <li class="page-item">
                                                <a class="page-link rounded-circle" href="{{ $products->url($last) }}">{{ $last }}</a>
                                            </li>
                                        @endif

                                        {{-- Next Page --}}
                                        <li class="page-item {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                                            <a class="page-link rounded-circle" 
                                               href="{{ $products->nextPageUrl() }}" 
                                               aria-label="{{ __('Next') }}">
                                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </li>

                                        {{-- Last Page --}}
                                        @if($products->currentPage() < $last - 1)
                                            <li class="page-item">
                                                <a class="page-link rounded-circle" 
                                                   href="{{ $products->url($last) }}" 
                                                   aria-label="{{ __('Last page') }}">
                                                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </li>
                                        @endif
                                    </ul>

                                    {{-- Mobile Pagination --}}
                                    <div class="d-flex d-md-none align-items-center justify-content-between w-100">
                                        {{-- Previous Button --}}
                                        <a href="{{ $products->previousPageUrl() }}" 
                                           class="btn btn-outline-terracotta rounded-pill px-4 py-2 {{ $products->onFirstPage() ? 'disabled' : '' }}"
                                           {{ $products->onFirstPage() ? 'aria-disabled="true"' : '' }}>
                                            <svg width="16" height="16" class="me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                            {{ __('Prev') }}
                                        </a>
                                        
                                        {{-- Page Info --}}
                                        <div class="text-center">
                                            <span class="badge bg-terracotta text-white rounded-pill px-3 py-2">
                                                <span class="fw-bold">{{ $products->currentPage() }}</span>
                                                <span class="opacity-75">/ {{ $products->lastPage() }}</span>
                                            </span>
                                        </div>
                                        
                                        {{-- Next Button --}}
                                        <a href="{{ $products->nextPageUrl() }}" 
                                           class="btn btn-terracotta rounded-pill px-4 py-2 {{ !$products->hasMorePages() ? 'disabled' : '' }}"
                                           {{ !$products->hasMorePages() ? 'aria-disabled="true"' : '' }}>
                                            {{ __('Next') }}
                                            <svg width="16" height="16" class="ms-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .card-hover:hover img {
        transform: scale(1.1);
    }
    
    .hover-opacity-100:hover {
        opacity: 1 !important;
    }
    
    /* Enhanced Pagination Styles */
    .pagination-modern {
        gap: 8px;
        margin: 0;
    }

    .pagination-modern .page-item {
        margin: 0;
    }

    .pagination-modern .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border: 2px solid #e9e5e0;
        background: white;
        color: #5d5348;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .pagination-modern .page-link.rounded-circle {
        border-radius: 50% !important;
    }

    .pagination-modern .page-link:hover {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-color: #fbbf24;
        color: #92400e;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.15);
    }

    .pagination-modern .page-link:active {
        transform: translateY(0);
    }

    /* Current page styling */
    .pagination-modern .page-item.active .page-link {
        background: linear-gradient(135deg, #9C6644, #7D4F35);
        border-color: #9C6644;
        color: white;
        box-shadow: 0 6px 20px rgba(156, 102, 68, 0.25);
        position: relative;
        transform: scale(1.05);
    }

    .pagination-modern .page-item.active .page-link::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 3px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 2px;
    }

    /* Disabled state */
    .pagination-modern .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #f3f4f6;
        color: #d1d5db;
        cursor: not-allowed;
        opacity: 0.7;
        transform: none;
        box-shadow: none;
    }

    .pagination-modern .page-item.disabled .page-link:hover {
        background: #f9fafb;
        border-color: #f3f4f6;
        color: #d1d5db;
        transform: none;
        box-shadow: none;
    }

    /* SVG icons styling */
    .pagination-modern .page-link svg {
        width: 20px;
        height: 20px;
    }

    .pagination-modern .page-link:hover svg {
        stroke: #92400e;
    }

    .pagination-modern .page-item.active .page-link svg {
        stroke: white;
    }

    /* Mobile pagination */
    @media (max-width: 767.98px) {
        .pagination-modern .page-link {
            width: 42px;
            height: 42px;
            font-size: 0.875rem;
        }
        
        .btn-terracotta, .btn-outline-terracotta {
            min-width: 100px;
        }
    }

    /* Animation for page changes */
    @keyframes pageTransition {
        0% {
            opacity: 0.5;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .pagination-modern .page-link {
        animation: pageTransition 0.3s ease-out;
    }

    /* Hover effect with subtle scale */
    .pagination-modern .page-link:hover {
        animation: hoverBounce 0.3s ease;
    }

    @keyframes hoverBounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-4px);
        }
    }

    /* Current page pulse effect */
    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 6px 20px rgba(156, 102, 68, 0.25);
        }
        50% {
            box-shadow: 0 6px 25px rgba(156, 102, 68, 0.4);
        }
    }

    .pagination-modern .page-item.active .page-link {
        animation: pulseGlow 2s infinite;
    }

    /* Focus state for accessibility */
    .pagination-modern .page-link:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(156, 102, 68, 0.3);
    }

    /* Per page selector styling */
    #per_page {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    #per_page:hover {
        background: #fef3c7 !important;
        box-shadow: 0 2px 8px rgba(156, 102, 68, 0.1);
    }

    #per_page:focus {
        box-shadow: 0 0 0 2px rgba(156, 102, 68, 0.2) !important;
        border-color: #9C6644 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 575.98px) {
        .pagination-info {
            text-align: center !important;
            margin-bottom: 1rem;
        }
        
        #per_page {
            width: 60px;
        }
    }

    /* RTL Support */
    [dir="rtl"] .pagination-modern .page-link svg {
        transform: rotate(180deg);
    }

    /* Responsive filters */
    @media (max-width: 991.98px) {
        #filterSidebar {
            display: none;
        }
        
        #filterSidebar.show {
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1050;
            background: white;
            overflow-y: auto;
        }
    }

    /* Sticky position for desktop */
    @media (min-width: 992px) {
        .position-sticky {
            position: -webkit-sticky;
            position: sticky;
        }
    }
</style>
@endpush

@push('scripts')
<script>
function toggleFilters() {
    const sidebar = document.getElementById('filterSidebar');
    const text = document.getElementById('filterText');
    
    if (window.innerWidth < 992) {
        sidebar.classList.toggle('show');
        text.textContent = sidebar.classList.contains('show') ? '{{ __('Hide Filters') }}' : '{{ __('Filters') }}';
    }
}

function addToCart(productId, variantId) {
    // Show loading state
    const button = event.currentTarget;
    const originalHTML = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    let url;
    let body = { quantity: 1 };
    
    if (variantId) {
        // Add by variant ID
        url = `{{ url(LaravelLocalization::getCurrentLocale() . '/cart/add') }}/${variantId}`;
    } else {
        // Add by product ID (uses first variant)
        url = `{{ url(LaravelLocalization::getCurrentLocale() . '/cart/add-product') }}/${productId}`;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(body)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update cart count in navigation
            updateCartCount(data.cart_count);
            
            // Show success toast
            showCartToast(data.message || '{{ __("Added to cart!") }}', 'success');
            
            // Reset button with success animation
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.add('btn-success');
            button.classList.remove('btn-terracotta');
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-terracotta');
                button.disabled = false;
            }, 1500);
        } else {
            showCartToast(data.message || '{{ __("Failed to add to cart") }}', 'error');
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showCartToast(error.message || '{{ __("An error occurred") }}', 'error');
        button.innerHTML = originalHTML;
        button.disabled = false;
    });
}

function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count, [data-cart-count]');
    cartCountElements.forEach(el => {
        el.textContent = count;
        // Animate the count update
        el.classList.add('animate-bounce');
        setTimeout(() => el.classList.remove('animate-bounce'), 500);
    });
}

function showCartToast(message, type) {
    const toast = document.createElement('div');
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    toast.className = `position-fixed top-0 end-0 m-4 px-4 py-3 rounded-3 shadow-lg ${bgClass} text-white d-flex align-items-center gap-2`;
    toast.style.zIndex = '9999';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'transform 0.3s ease';
    
    const icon = type === 'success' ? 
        '<i class="fas fa-check-circle"></i>' : 
        '<i class="fas fa-exclamation-circle"></i>';
    
    toast.innerHTML = `${icon} <span>${message}</span>`;
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

function updatePerPage(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', value);
    url.searchParams.delete('page'); // Reset to first page when changing per page
    window.location.href = url.toString();
}

// Smooth scroll for animations
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

    // Add keyboard navigation for pagination
    document.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'BODY') {
            const currentPage = {{ $products->currentPage() }};
            const lastPage = {{ $products->lastPage() }};
            
            if (e.key === 'ArrowLeft' && currentPage > 1) {
                window.location.href = '{{ $products->previousPageUrl() }}';
            } else if (e.key === 'ArrowRight' && currentPage < lastPage) {
                window.location.href = '{{ $products->nextPageUrl() }}';
            }
        }
    });

    // Add smooth scroll to top on page change
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination-modern .page-link') && !e.target.closest('.page-item.disabled')) {
            e.preventDefault();
            const href = e.target.closest('.page-link').href;
            
            // Smooth scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            
            // Navigate after scroll
            setTimeout(() => {
                window.location.href = href;
            }, 300);
        }
    });

    // Add loading state to pagination links
    const paginationLinks = document.querySelectorAll('.pagination-modern .page-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.closest('.page-item.disabled')) {
                // Create loading spinner
                const spinner = document.createElement('span');
                spinner.className = 'spinner-border spinner-border-sm ms-2';
                spinner.style.width = '1rem';
                spinner.style.height = '1rem';
                spinner.style.borderWidth = '0.15em';
                
                // Add spinner and disable link
                this.appendChild(spinner);
                this.style.pointerEvents = 'none';
                this.style.opacity = '0.8';
                
                // Add loading text if available
                const originalText = this.innerHTML;
                this.innerHTML = originalText.replace(/(<\/svg>)/, '$1<span class="ms-1">{{ __("Loading...") }}</span>');
            }
        });
    });

    // Close mobile filters when clicking outside
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('filterSidebar');
        const filterToggle = document.getElementById('filterToggle');
        
        if (sidebar && sidebar.classList.contains('show') && 
            !sidebar.contains(e.target) && 
            !filterToggle.contains(e.target) &&
            window.innerWidth < 992) {
            sidebar.classList.remove('show');
            document.getElementById('filterText').textContent = '{{ __("Filters") }}';
        }
    });
});

// Add animation classes
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .animate-fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-fade-in-up.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .animation-delay-100 { transition-delay: 0.1s; }
        .animation-delay-200 { transition-delay: 0.2s; }
        .animation-delay-300 { transition-delay: 0.3s; }
        .animation-delay-400 { transition-delay: 0.4s; }
        .animation-delay-500 { transition-delay: 0.5s; }
    `;
    document.head.appendChild(style);
});
</script>
@endpush