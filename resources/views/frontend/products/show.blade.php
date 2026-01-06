@extends('layouts.frontend')

@section('title', ($product->name[app()->getLocale()] ?? $product->name['en']) . ' - ' . __('NounieStore'))

@section('content')
<div class="bg-linen min-vh-100">
    <!-- Breadcrumb -->
    <div class="bg-white border-bottom border-sand">
        <div class="container py-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }}">
                    <li class="breadcrumb-item">
                        <a href="{{ LaravelLocalization::localizeUrl(route('home')) }}" class="text-stone text-decoration-none hover-clay">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ LaravelLocalization::localizeUrl(route('products.index')) }}" class="text-stone text-decoration-none hover-clay">
                            {{ __('Products') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ LaravelLocalization::localizeUrl(route('products.index', ['categories' => [$product->category->id]])) }}" class="text-stone text-decoration-none hover-clay">
                            {{ $product->category->name[app()->getLocale()] ?? $product->category->name['en'] }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-charcoal fw-medium" aria-current="page">
                        {{ $product->name[app()->getLocale()] ?? $product->name['en'] }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        @php
            // Calculate stock for each variant (use quantity OR stock field)
            $variantsWithStock = $product->variants->map(function($v) {
                $stock = $v->quantity ?? $v->stock ?? 0;
                return [
                    'id' => $v->id, 
                    'name' => $v->name[app()->getLocale()] ?? $v->name['en'] ?? 'Variant', 
                    'price' => floatval($v->price), 
                    'compare_at_price' => floatval($v->compare_at_price ?? 0), 
                    'quantity' => $stock
                ];
            });
            $firstVariantStock = $product->variants->first() ? ($product->variants->first()->quantity ?? $product->variants->first()->stock ?? 0) : 0;
        @endphp
        <div x-data="{
            activeImage: 0,
            selectedVariantId: {{ $product->variants->first()->id ?? 0 }},
            quantity: 1,
            maxQuantity: {{ $firstVariantStock > 0 ? $firstVariantStock : 1 }},
            productImages: {{ $product->images->map(function($img) { return ['id' => $img->id, 'url' => $img->url]; })->toJson() }},
            variants: {{ $variantsWithStock->toJson() }},
            getSelectedVariantImages() {
                return this.productImages;
            },
            getVariantPrice(variantId) {
                const variant = this.variants.find(v => v.id == variantId);
                return variant ? Number(variant.price).toFixed(2) : '0.00';
            },
            getVariantComparePrice(variantId) {
                const variant = this.variants.find(v => v.id == variantId);
                return variant && variant.compare_at_price ? Number(variant.compare_at_price).toFixed(2) : null;
            },
            getVariantQuantity(variantId) {
                const variant = this.variants.find(v => v.id == variantId);
                return variant ? variant.quantity : 0;
            },
            updateActiveImage() {
                this.activeImage = 0;
            },
            selectVariant(variantId) {
                this.selectedVariantId = variantId;
                this.maxQuantity = this.getVariantQuantity(variantId) || 1;
                if (this.quantity > this.maxQuantity) {
                    this.quantity = this.maxQuantity;
                }
                this.$dispatch('variant-changed');
            },
            decreaseQuantity() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },
            increaseQuantity() {
                if (this.quantity < this.maxQuantity) {
                    this.quantity++;
                }
            },
            addToCart(productId, variantId, qty) {
                window.addToCartGlobal(productId, variantId, qty);
            }
        }" x-init="updateActiveImage()" @variant-changed="updateActiveImage()" class="row g-4 mb-5">
            <!-- Product Images -->
            <div class="col-lg-6">
                <div>
                    <!-- Main Image -->
                    <div class="bg-white rounded-4 shadow-lg border border-sand overflow-hidden mb-3 product-image-zoom">
                        <template x-if="getSelectedVariantImages().length > 0">
                            <template x-for="(image, index) in getSelectedVariantImages()" :key="image.id">
                                <img x-show="activeImage === index"
                                     :src="image.url"
                                     alt="{{ $product->name[app()->getLocale()] ?? $product->name['en'] }}"
                                     class="w-100 object-fit-cover"
                                     style="height: 500px;">
                            </template>
                        </template>
                        <template x-if="getSelectedVariantImages().length === 0">
                            <div class="w-100 d-flex align-items-center justify-content-center bg-gradient" style="height: 500px; background: linear-gradient(135deg, var(--bs-parchment), var(--bs-sand));">
                                <svg class="text-ochre opacity-50" width="128" height="128" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </template>
                    </div>

                    <!-- Thumbnails -->
                    <template x-if="getSelectedVariantImages().length > 1">
                        <div class="row g-2">
                            <template x-for="(image, index) in getSelectedVariantImages()" :key="image.id">
                                <div class="col-3">
                                    <button @click="activeImage = index"
                                            :class="{'border-clay border-3': activeImage === index, 'border-sand': activeImage !== index}"
                                            class="bg-white rounded-3 overflow-hidden border hover-border-clay w-100 p-0 btn transition-all"
                                            type="button">
                                        <img :src="image.url"
                                             alt="{{ $product->name[app()->getLocale()] ?? $product->name['en'] }}"
                                             class="w-100 object-fit-cover" style="height: 100px; object-fit: cover;">
                                    </button>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <p class="text-clay fw-semibold mb-2 text-uppercase small">
                        {{ $product->category->name[app()->getLocale()] ?? $product->category->name['en'] }}
                    </p>
                    <h1 class="display-5 fw-bold text-charcoal mb-4">
                        {{ $product->name[app()->getLocale()] ?? $product->name['en'] }}
                    </h1>
                    
                    <!-- Reviews & Wishlist -->
                    <div class="d-flex align-items-center justify-content-between mb-4 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }}">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="{{ $i < 4 ? 'text-clay' : 'text-sand' }}" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @endfor
                            </div>
                            <span class="small text-stone">(128 {{ __('reviews') }})</span>
                        </div>
                        @auth
                        <button onclick="toggleWishlist({{ $product->id }})" 
                                id="wishlist-btn-{{ $product->id }}"
                                class="btn btn-link text-clay hover-ochre text-decoration-none d-flex align-items-center gap-2 p-0" 
                                type="button">
                            <svg id="wishlist-icon-{{ $product->id }}" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span id="wishlist-text-{{ $product->id }}" class="fw-medium small">{{ __('Add to Wishlist') }}</span>
                        </button>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-link text-clay hover-ochre text-decoration-none d-flex align-items-center gap-2 p-0">
                            <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="fw-medium small">{{ __('Add to Wishlist') }}</span>
                        </a>
                        @endauth
                    </div>
                    
                    @if($product->variants->count() > 0)
                        @if($product->variants->count() > 1)
                        <!-- Variant Selection -->
                        <div class="mb-4">
                            <h5 class="fw-semibold text-charcoal mb-3">{{ __('Select Variant') }}</h5>
                            <div class="row g-2">
                                @foreach($product->variants as $variant)
                                @php $variantStock = $variant->quantity ?? $variant->stock ?? 0; @endphp
                                <div class="col-6 col-md-4">
                                    <button @click="selectVariant({{ $variant->id }})"
                                            :class="selectedVariantId === {{ $variant->id }} ? 'btn-clay text-white' : 'btn-outline-sand'"
                                            class="btn w-100 px-3 py-3 rounded-3 small fw-medium text-start transition-all {{ $variantStock <= 0 ? 'opacity-50' : '' }}"
                                            {{ $variantStock <= 0 ? 'disabled' : '' }}
                                            type="button">
                                        <div class="fw-semibold">{{ $variant->name[app()->getLocale()] ?? $variant->name['en'] ?? 'Variant' }}</div>
                                        <div class="text-primary">${{ number_format($variant->price, 2) }}</div>
                                        @if($variantStock <= 0)
                                        <div class="text-danger small">{{ __('Out of Stock') }}</div>
                                        @else
                                        <div x-show="selectedVariantId === {{ $variant->id }}" class="mt-1">
                                            <svg class="text-clay" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 0 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                            </svg>
                                            <span class="text-clay small fw-medium">{{ __('Selected') }}</span>
                                        </div>
                                        @endif
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Price Display -->
                        <div class="d-flex align-items-baseline gap-3 mb-4 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }}">
                            <span class="display-4 fw-bold text-clay" x-text="'$' + getVariantPrice(selectedVariantId)">
                                ${{ number_format($product->variants->first()->price, 2) }}
                            </span>
                            <span x-show="getVariantComparePrice(selectedVariantId) && parseFloat(getVariantComparePrice(selectedVariantId)) > parseFloat(getVariantPrice(selectedVariantId))" class="fs-4 text-stone text-decoration-line-through" x-text="'$' + getVariantComparePrice(selectedVariantId)">
                            </span>
                            <span x-show="getVariantComparePrice(selectedVariantId) && parseFloat(getVariantComparePrice(selectedVariantId)) > parseFloat(getVariantPrice(selectedVariantId))" class="badge bg-terracotta bg-opacity-25 text-white fs-6 px-3 py-2" x-text="'-' + Math.round(((parseFloat(getVariantComparePrice(selectedVariantId)) - parseFloat(getVariantPrice(selectedVariantId))) / parseFloat(getVariantComparePrice(selectedVariantId))) * 100) + '%'">
                            </span>
                        </div>
                    @endif
                </div>

                <div class="border-top border-bottom border-sand py-4 mb-4">
                    <div class="text-stone">
                        {!! nl2br(e($product->description[app()->getLocale()] ?? $product->description['en'] ?? '')) !!}
                    </div>
                </div>

                <!-- Key Features -->
                @if($product->features && count($product->features) > 0)
                <div class="bg-linen rounded-4 p-4 mb-4">
                    <h3 class="fs-5 fw-bold text-charcoal mb-3">{{ __('Key Features') }}</h3>
                    <ul class="list-unstyled mb-0">
                        @foreach($product->features as $index => $feature)
                        <li class="d-flex align-items-start gap-3 {{ $loop->last ? '' : 'mb-3' }} {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }}">
                            <svg class="text-clay flex-shrink-0" width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="margin-top: 2px;">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-stone">{{ is_array($feature) ? ($feature[app()->getLocale()] ?? $feature['en'] ?? '') : $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Variants -->
                {{-- @if($product->variants->count() > 1)
                <div x-data="{ selectedVariant: {{ $product->variants->first()->id }} }" class="mb-4">
                    <h3 class="small fw-medium text-charcoal mb-3">{{ __('Options') }}</h3>
                    <div class="row g-2">
                        @foreach($product->variants as $variant)
                        <div class="col-4">
                            <button @click="selectedVariant = {{ $variant->id }}"
                                    :class="{'border-clay border-2 bg-parchment': selectedVariant === {{ $variant->id }}}"
                                    class="btn w-100 px-4 py-3 border border-sand rounded-3 hover-border-clay small fw-medium {{ $variant->quantity <= 0 ? 'opacity-50' : '' }}"
                                    {{ $variant->quantity <= 0 ? 'disabled' : '' }}
                                    type="button">
                                {{ $variant->name[app()->getLocale()] ?? $variant->name['en'] }}
                                @if($variant->quantity <= 0)
                                <span class="d-block text-danger" style="font-size: 0.75rem;">{{ __('Out of Stock') }}</span>
                                @endif
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif --}}

                <!-- Quantity & Add to Cart -->
                <div class="mb-4">
                    <label class="form-label small fw-medium text-charcoal">{{ __('Quantity') }}</label>
                    <div class="d-flex align-items-center gap-3 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }}">
                        <button @click="decreaseQuantity()"
                                :disabled="quantity <= 1"
                                class="btn btn-outline-sand rounded-3 d-flex align-items-center justify-content-center hover-bg-parchment text-clay disabled:opacity-50"
                                style="width: 40px; height: 40px;"
                                type="button">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </button>
                        <input x-model.number="quantity"
                               type="number"
                               min="1"
                               :max="maxQuantity"
                               class="form-control text-center border-sand rounded-3"
                               style="width: 80px;">
                        <button @click="increaseQuantity()"
                                :disabled="quantity >= (getVariantQuantity(selectedVariantId) || 99)"
                                class="btn btn-outline-sand rounded-3 d-flex align-items-center justify-content-center hover-bg-parchment text-clay disabled:opacity-50"
                                style="width: 40px; height: 40px;"
                                type="button">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>

                @if($product->variants->count() > 0)
                    @php $firstAvailableVariant = $product->variants->first(fn($v) => ($v->quantity ?? $v->stock ?? 0) > 0); @endphp
                    @if($firstAvailableVariant)
                    <!-- Selected Variant Info -->
                    <div class="mb-3 p-3 bg-parchment rounded-3 border border-sand">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-charcoal fw-medium">{{ __('Selected:') }}</span>
                                <span class="text-clay fw-semibold" x-text="variants.find(v => v.id == selectedVariantId)?.name || '{{ __('None') }}'"></span>
                            </div>
                            <div class="text-end">
                                <div class="text-clay fw-bold fs-5" x-text="'$' + getVariantPrice(selectedVariantId)"></div>
                                <div x-show="getVariantComparePrice(selectedVariantId) && parseFloat(getVariantComparePrice(selectedVariantId)) > parseFloat(getVariantPrice(selectedVariantId))" class="text-stone text-decoration-line-through" x-text="'$' + getVariantComparePrice(selectedVariantId)"></div>
                            </div>
                        </div>
                    </div>

                    <button id="add-to-cart-btn"
                            @click="addToCart({{ $product->id }}, selectedVariantId, quantity)"
                            :disabled="getVariantQuantity(selectedVariantId) <= 0"
                            class="btn btn-clay w-100 py-3 rounded-3 hover-bg-ochre fw-semibold fs-5 d-flex align-items-center justify-content-center gap-2 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }} shadow-lg hover-shadow-xl transition-all disabled:opacity-50"
                            type="button">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span x-text="getVariantQuantity(selectedVariantId) <= 0 ? '{{ __('Out of Stock') }}' : '{{ __('Add to Cart') }}'"></span>
                    </button>
                    @else
                    <button disabled class="btn btn-secondary w-100 py-3 rounded-3 fw-semibold fs-5 opacity-50" type="button">
                        {{ __('Out of Stock') }}
                    </button>
                    @endif
                @endif                <!-- Trust Badges -->
                <div class="bg-parchment rounded-4 p-4 my-4">
                    <div class="row g-4 text-center">
                        <div class="col-4">
                            <div class="rounded-circle bg-clay bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                               <i class="fas fa-check text-white"></i>
                            </div>
                            <p class="fw-semibold text-charcoal mb-0" style="font-size: 0.75rem;">{{ __('Quality') }}</p>
                            <p class="text-stone mb-0" style="font-size: 0.75rem;">{{ __('Guaranteed') }}</p>
                        </div>
                        <div class="col-4">
                            <div class="rounded-circle bg-ochre bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                              <i class="fas fa-shipping-fast text-white"></i>
                            </div>
                            <p class="fw-semibold text-charcoal mb-0" style="font-size: 0.75rem;">{{ __('Fast') }}</p>
                            <p class="text-stone mb-0" style="font-size: 0.75rem;">{{ __('Shipping') }}</p>
                        </div>
                        <div class="col-4">
                            <div class="rounded-circle bg-terracotta bg-opacity-25 d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 48px; height: 48px;">
                                 <i class="fas fa-lock text-white"></i>
                            </div>
                            <p class="fw-semibold text-charcoal mb-0" style="font-size: 0.75rem;">{{ __('Secure') }}</p>
                            <p class="text-stone mb-0" style="font-size: 0.75rem;">{{ __('Payment') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="d-flex align-items-center gap-2 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'flex-row-reverse' : '' }} small">
                    <template x-if="getVariantQuantity(selectedVariantId) > 0">
                        <div class="d-flex align-items-center gap-2">
                            <svg class="text-clay" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-clay fw-medium" x-text="'{{ __('In Stock') }} (' + getVariantQuantity(selectedVariantId) + ' {{ __('available') }})'"></span>
                        </div>
                    </template>
                    <template x-if="getVariantQuantity(selectedVariantId) <= 0">
                        <div class="d-flex align-items-center gap-2">
                            <svg class="text-terracotta" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-danger fw-medium">{{ __('Out of Stock') }}</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- End of x-data component -->

        <!-- Related Products -->
        @if($relatedProducts->isNotEmpty())
        <div class="mt-5">
            <h2 class="fs-2 fw-bold text-charcoal mb-4">{{ __('Related Products') }}</h2>
            <div class="row g-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-sand overflow-hidden h-100 product-card-hover">
                        <a href="{{ $relatedProduct->slug ? LaravelLocalization::localizeUrl(route('products.show', $relatedProduct->slug)) : '#' }}" class="position-relative d-block overflow-hidden">
                            @if($relatedProduct->images && $relatedProduct->images->isNotEmpty())
                            <img src="{{ $relatedProduct->images->first()->url }}" 
                                 alt="{{ $relatedProduct->name[app()->getLocale()] ?? $relatedProduct->name['en'] }}" 
                                 class="card-img-top object-fit-cover product-image-scale"
                                 style="height: 200px;">
                            @else
                            <div class="d-flex align-items-center justify-content-center bg-gradient" style="height: 200px; background: linear-gradient(135deg, var(--bs-parchment), var(--bs-sand));">
                                <svg class="text-ochre opacity-50" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            @endif
                        </a>
                        <div class="card-body">
                            <h3 class="card-title fw-semibold text-charcoal mb-2 lh-sm" style="font-size: 1rem;">
                                <a href="{{ $relatedProduct->slug ? LaravelLocalization::localizeUrl(route('products.show', $relatedProduct->slug)) : '#' }}" class="text-charcoal text-decoration-none hover-clay stretched-link">
                                    {{ $relatedProduct->name[app()->getLocale()] ?? $relatedProduct->name['en'] }}
                                </a>
                            </h3>
                            @if($relatedProduct->variants->first())
                            <p class="fs-5 fw-bold text-clay mb-0">
                                ${{ number_format($relatedProduct->variants->first()->price, 2) }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleWishlist(productId) {
    const button = document.getElementById(`wishlist-btn-${productId}`);
    const icon = document.getElementById(`wishlist-icon-${productId}`);
    const text = document.getElementById(`wishlist-text-${productId}`);
    const originalHTML = button.innerHTML;
    
    button.disabled = true;
    
    fetch(`/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.inWishlist) {
                icon.setAttribute('fill', 'currentColor');
                text.textContent = '{{ __("Remove from Wishlist") }}';
                button.classList.add('text-danger');
                button.classList.remove('text-clay');
            } else {
                icon.setAttribute('fill', 'none');
                text.textContent = '{{ __("Add to Wishlist") }}';
                button.classList.remove('text-danger');
                button.classList.add('text-clay');
            }
            showToast(data.message, 'success');
        } else {
            showToast(data.message, 'error');
        }
        button.disabled = false;
    })
    .catch(error => {
        showToast('{{ __("An error occurred. Please try again.") }}', 'error');
        button.disabled = false;
    });
}

function addToCartGlobal(productId, variantId, quantity) {
    // Find the add to cart button by ID
    const button = document.getElementById('add-to-cart-btn');
    let originalHTML = '';
    
    if (button) {
        originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> {{ __("Adding...") }}';
    }

    let url;
    let body = { quantity: quantity || 1 };
    
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
            showToast(data.message || '{{ __("Added to cart!") }}', 'success');
            
            // Reset button with success animation
            if (button) {
                button.innerHTML = '<i class="fas fa-check me-2"></i> {{ __("Added!") }}';
                button.classList.add('btn-success');
                button.classList.remove('btn-clay');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-clay');
                    button.disabled = false;
                }, 1500);
            }
        } else {
            showToast(data.message || '{{ __("Failed to add to cart") }}', 'error');
            if (button) {
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(error.message || '{{ __("An error occurred") }}', 'error');
        if (button) {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    });
}

function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count, .cart-count-badge, [data-cart-count]');
    cartCountElements.forEach(el => {
        if (count !== undefined) {
            el.textContent = count;
        }
        // Animate the count update
        el.classList.add('animate-bounce');
        el.classList.remove('d-none');
        setTimeout(() => el.classList.remove('animate-bounce'), 500);
    });
}

function showToast(message, type) {
    const toast = document.createElement('div');
    const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
    toast.className = `position-fixed top-0 end-0 m-4 px-4 py-3 rounded-3 shadow-lg ${bgClass} text-white d-flex align-items-center gap-2`;
    toast.style.zIndex = '9999';
    
    const icon = type === 'success' ? 
        '<i class="fas fa-check-circle"></i>' : 
        '<i class="fas fa-exclamation-circle"></i>';
    
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

<script>
// Alert Dialog Component Data for Products
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
if (!window.showAlertDialog) {
    window.showAlertDialog = function(options) {
        const dialogElement = document.querySelector('[x-data="alertDialog"]');
        if (dialogElement) {
            // Alpine.js 3.x uses _x_dataStack
            const alpineData = Alpine.$data(dialogElement);
            if (alpineData && alpineData.show) {
                alpineData.show(options);
            }
        }
    };
}
</script>

<style>
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

<style>
.product-image-zoom img {
    transition: transform 0.5s ease;
}

.product-image-zoom:hover img {
    transform: scale(1.05);
}

.product-card-hover {
    transition: all 0.3s ease;
}

.product-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
}

.product-image-scale {
    transition: transform 0.5s ease;
}

.product-card-hover:hover .product-image-scale {
    transform: scale(1.1);
}

.hover-shadow-xl:hover {
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    transform: translateY(-2px);
}

.transition-all {
    transition: all 0.3s ease;
}

.lh-sm {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* RTL Support for breadcrumb */
[dir="rtl"] .breadcrumb-item + .breadcrumb-item::before {
    float: right;
    padding-left: 0.5rem;
    padding-right: 0;
}
</style>
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
