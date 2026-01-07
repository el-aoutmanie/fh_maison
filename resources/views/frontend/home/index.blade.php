@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
    $locale = app()->getLocale();
@endphp

@extends('layouts.frontend')

@section('title', __('Home') . ' - ' . config('app.name'))

@section('meta')
    <meta name="description" content="{{ __('Discover handcrafted artisanal products, carefully crafted with traditional techniques and modern design. Shop unique items for your home and lifestyle.') }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ __('Premium artisanal products crafted with passion') }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap');

    :root {
        --primary-color: #5c4b3e;
        --secondary-color: #8c7b6e;
        --bg-color: #fcfbf9;
        --text-color: #2d2d2d;
        --font-serif: 'Playfair Display', 'Georgia', serif;
        --font-sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    body {
        font-family: var(--font-sans);
        background-color: var(--bg-color);
        color: var(--text-color);
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-serif);
        color: var(--primary-color);
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 400;
        margin-bottom: 1rem;
        text-align: center;
    }

    .section-subtitle {
        font-family: var(--font-sans);
        font-size: 1rem;
        color: var(--secondary-color);
        text-align: center;
        margin-bottom: 3rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Main Hero Section */
    .main-hero {
        position: relative;
        width: 100%; /* Full viewport width */
        min-height: 80vh; /* Taller hero */
        background-color: #eaddcf; /* Fallback beige */
        background-image: url('{{ asset('assets/heresection.png') }}');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 0;
    }
    
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(247, 243, 237, 0.3); /* Light transparent overlay text visibility */
    }

    .hero-container {
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 0 5%;
    }

    .hero-content {
        max-width: 650px;
    }

    .hero-title {
        font-family: var(--font-serif);
        font-size: 4.5rem; /* Larger title */
        line-height: 1.1;
        margin-bottom: 1.5rem;
        color: #4a3b32;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .hero-subtitle {
        font-family: var(--font-serif);
        font-size: 1.5rem;
        color: #4a3b32;
        margin-bottom: 1.5rem;
        font-style: italic;
    }

    .hero-text {
        font-size: 1.1rem;
        color: #4a3b32;
        margin-bottom: 2.5rem;
        line-height: 1.6;
        font-weight: 500;
    }

    /* Buttons */
    .btn-custom-primary {
        background-color: #5c4b3e;
        color: white;
        padding: 1rem 2.5rem;
        border-radius: 2px;
        border: 1px solid #5c4b3e;
        transition: all 0.3s;
        font-family: var(--font-sans);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-custom-outline {
        background-color: rgba(255,255,255,0.7);
        color: #5c4b3e;
        padding: 1rem 2.5rem;
        border-radius: 2px;
        border: 1px solid #5c4b3e;
        transition: all 0.3s;
        font-family: var(--font-sans);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        display: inline-block;
        margin-left: 1rem;
    }
    
    [dir="rtl"] .btn-custom-outline {
        margin-left: 0;
        margin-right: 1rem;
    }

    .btn-custom-primary:hover {
        background-color: #4a3b32;
        border-color: #4a3b32;
        color: white;
    }

    .btn-custom-outline {
        background-color: transparent;
        color: #5c4b3e;
        padding: 0.8rem 2rem;
        border-radius: 2px;
        border: 1px solid #5c4b3e;
        transition: all 0.3s;
        font-family: var(--font-sans);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-custom-outline:hover {
        background-color: #5c4b3e;
        color: white;
    }

    /* Categories */
    .category-item {
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform 0.3s ease;
    }

    .category-item:hover {
        transform: translateY(-5px);
    }

    .category-item:hover .category-img {
        transform: scale(1.05);
    }

    .category-img-wrapper {
        overflow: hidden;
        margin-bottom: 1rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        aspect-ratio: 1;
    }

    .category-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .category-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .category-desc {
        font-size: 0.9rem;
        color: var(--secondary-color);
        line-height: 1.5;
    }

    .category-name {
        font-size: 1.4rem;
        margin-bottom: 0.5rem;
        color: #4a3b32;
    }

    .category-desc {
        font-size: 0.9rem;
        color: #777;
        line-height: 1.5;
    }

    /* Products */
    .product-item {
        margin-bottom: 2rem;
        background: transparent;
        border: none;
    }

    .product-img-wrapper {
        position: relative;
        margin-bottom: 1rem;
        overflow: hidden;
        border-radius: 4px;
    }

    .product-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .product-name{
        text-transform: capitalize
    }
    .product-info h3 {
        font-size: 1.1rem;
        font-family: var(--font-sans);
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .product-price {
        font-size: 1rem;
        color: #666;
        margin-bottom: 1rem;
    }

    .btn-product {
        display: block;
        width: 100%;
        background-color: #5c4b3e;
        color: white;
        text-align: center;
        padding: 0.6rem;
        text-decoration: none;
        font-size: 0.9rem;
        border-radius: 2px;
        transition: background 0.3s;
    }

    .btn-product:hover {
        background-color: #4a3b32;
        color: white;
    }

    /* Artisan Section */
    .artisan-section {
        padding: 5rem 0;
        background-color: var(--bg-color);
    }

    .artisan-title {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        color: #4a3b32;
    }

    .artisan-desc {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 2rem;
    }

    /* Values */
    .values-section {
        padding: 4rem 0;
        background-color: #fcfbf9;
        border-top: 1px solid #eee;
    }

    .values-list {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 2rem;
        font-style: italic;
        color: #666;
        font-family: var(--font-serif);
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 2.5rem; }
        .hero-image { height: 300px; margin-top: 2rem; }
        .category-img, .product-img { height: 250px; }
    }
</style>
@endpush

@section('content')
<!-- Main Hero Section -->
<section class="main-hero">
    <div class="hero-overlay"></div>
    <div class="hero-container" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="hero-content">
            <h1 class="hero-title">FH MAISON DE<br>L'ARTISANAT</h1>
            <h2 class="hero-subtitle">{{ __('Des créations faites main, pour sublimer le quotidien.') }}</h2>
            <p class="hero-text">
                {{ __('Un univers chic et commun, objets précieux, traiteur et créations sur mesure.') }}
            </p>
            <div class="d-flex align-items-center flex-wrap">
                <a href="{{ route('products.index') }}" class="btn-custom-primary">{{ __('Découvrir la boutique') }}</a>
                <a href="{{ route('about') }}" class="btn-custom-outline">{{ __('En savoir plus sur l\'atelier') }}</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-5 bg-white">
    <div class="container" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="text-center mb-5">
            <h2 class="section-title">{{ __('Shop by Category') }}</h2>
            <p class="section-subtitle">{{ __('Discover our curated collection of handcrafted products') }}</p>
        </div>

        @if($categories->count() > 0)
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="{{ LaravelLocalization::localizeUrl(route('products.index', ['categories' => [$category->id]])) }}" 
                           class="category-item text-decoration-none">
                            <div class="category-img-wrapper">
                                @if($category->image_url)
                                    <img src="{{ $category->image_url }}" 
                                         alt="{{ $category->name[$locale] ?? $category->name['en'] }}" 
                                         class="category-img">
                                @elseif($category->getFirstMediaUrl('categories'))
                                    <img src="{{ $category->getFirstMediaUrl('categories') }}" 
                                         alt="{{ $category->name[$locale] ?? $category->name['en'] }}" 
                                         class="category-img">
                                @else
                                    <div class="category-img d-flex align-items-center justify-content-center" 
                                         style="background: linear-gradient(135deg, #e8d5c4 0%, #c4b5a0 100%);">
                                        <i class="fas fa-box-open text-white" style="font-size: 3rem; opacity: 0.7;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center mt-3">
                                <h3 class="category-name mb-1">{{ $category->name[$locale] ?? $category->name['en'] }}</h3>
                                <p class="text-muted small mb-0">
                                    {{ $category->products_count }} {{ $category->products_count === 1 ? __('Product') : __('Products') }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- View All Categories Button -->
            <div class="text-center mt-5">
                <a href="{{ LaravelLocalization::localizeUrl(route('products.index')) }}" class="btn btn-custom-outline">
                    {{ __('View All Products') }}
                    <i class="fas fa-arrow-right ms-2 {{ $isRtl ? 'd-none' : '' }}"></i>
                    <i class="fas fa-arrow-left me-2 {{ $isRtl ? '' : 'd-none' }}"></i>
                </a>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                <p class="text-muted mt-3">{{ __('No categories available yet') }}</p>
            </div>
        @endif
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured" class="py-5">
    <div class="container" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="text-center mb-5">
            <h2 class="section-title">{{ __('Selection of this moment') }}</h2>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts->take(4) as $product)
                @php
                    $productSlug = is_array($product->slug) ? ($product->slug[$locale] ?? $product->slug['en'] ?? '') : $product->slug;
                    $productName = is_array($product->name) ? ($product->name[$locale] ?? $product->name['en'] ?? '') : $product->name;
                @endphp
                <div class="col-sm-6 col-lg-3">
                    <div class="product-item">
                        <div class="product-img-wrapper">
                            @if($product->images && $product->images->isNotEmpty())
                                <img src="{{ $product->images->first()->url }}" alt="{{ $productName }}" class="product-img">
                            @else
                                <div class="product-img" style="background-color: #f0f0f0;"></div>
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ Str::limit($productName, 50) }}</h3>
                            <div class="product-price">
                                @if($product->price)
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        {{ number_format($product->sale_price, 2) }} {{ __('MAD') }}
                                    @else
                                        {{ number_format($product->price, 2) }} {{ __('MAD') }}
                                    @endif
                                @elseif($product->variants->first())
                                    {{ number_format($product->variants->first()->price, 2) }} {{ __('MAD') }}
                                @endif
                            </div>
                            <a href="{{ $productSlug ? LaravelLocalization::localizeUrl(route('products.show', $productSlug)) : '#' }}" class="btn-product">
                                {{ __('See Product') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Artisan Section -->
<section class="artisan-section">
    <div class="container">
        <div class="row align-items-center" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div style="overflow: hidden; border-radius: 4px;">
                    <div class="artisan-image" style="background: url('{{ asset('assets/second-herosection.jpg') }}') center/cover no-repeat; height: 450px; background-color: #c4b5a0;"></div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h2 class="artisan-title">{{ __('L\'atelier FH, entre tradition et modernité') }}</h2>
                <p class="artisan-desc">
                    {{ __('L\'atelier artisanal de pièces authentiques, créé avec passion pour apporter chaleur et caractère à vos espaces. Chaque création raconte une histoire unique, née du savoir-faire traditionnel et de l\'innovation contemporaine.') }}
                </p>
                <a href="{{ LaravelLocalization::localizeUrl(route('about')) }}" class="btn-custom-outline">
                    {{ __('Découvrir notre histoire') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section">
    <div class="container" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <h2 class="section-title mb-5">{{ __('Our values') }}</h2>
        <div class="values-list">
            <span>• {{ __('Un projet vue prenasse somma um efocke') }}</span>
            <span>• {{ __('Offis statute alleacun.') }}</span>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function addToCart(productId, variantId = null) {
        // Cart functionality placeholder
        console.log('Add to cart:', productId, variantId);
        alert('{{ __("Produit ajouté au panier!") }}');
    }
</script>
@endpush
