<nav class="bg-white border-bottom border-gray-200 shadow-sm sticky-top" style="z-index: 1030;">
    @php
        $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
    @endphp
    
    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white py-2" style="background: linear-gradient(135deg, #d97706, #b45309);" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 text-center">
                    <div class="d-inline-flex align-items-center gap-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <i class="fas fa-gift" style="font-size: 0.9rem;"></i>
                        <span class="fw-medium">{{ __('Free Shipping Worldwide') }}</span>
                        <span class="mx-2 opacity-75">•</span>
                        <span class="d-none d-md-inline">{{ __('Orders Over $50') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="container py-3" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="d-flex align-items-center justify-content-between {{ $isRtl ? 'flex-row-reverse' : '' }}">
            
            <!-- Logo & Mobile Menu -->
            <div class="d-flex align-items-center gap-4 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                <!-- Mobile Menu Button -->
                <button class="btn btn-outline-light d-lg-none p-2 rounded-3" 
                        onclick="toggleMobileMenu()"
                        style="border: none; color: #4b5563;">
                    <i class="fas fa-bars" style="font-size: 1.2rem;"></i>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-3 logo-hover">
                    <div class="position-relative">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" 
                             style="width: 48px; height: 48px; background: linear-gradient(135deg, #d97706, #92400e); box-shadow: 0 4px 12px rgba(217, 119, 6, 0.2);">
                            <span class="text-white fw-bold" style="font-size: 1.1rem;">AS</span>
                        </div>
                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-3" 
                             style="background: rgba(245, 158, 11, 0.2); filter: blur(8px); opacity: 0; transition: opacity 0.3s ease;"></div>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-900" style="font-size: 1.25rem; letter-spacing: -0.5px;">
                            Artisan<span class="text-amber-600">Store</span>
                        </div>
                        <div class="text-gray-500" style="font-size: 0.75rem; font-weight: 500; letter-spacing: 0.5px;">
                            {{ __('Premium Handcrafted Goods') }}
                        </div>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="d-none d-lg-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                <a href="{{ route('home') }}" 
                   class="nav-link px-4 py-2.5 rounded-3 d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('home') ? 'active-nav' : 'nav-link-hover' }}"
                   style="font-weight: 500;">
                    {{ __('Home') }}
                </a>
                
                <!-- Shop Dropdown -->
                <div class="dropdown position-relative">
                    <button class="nav-link px-4 py-2.5 rounded-3 d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }} dropdown-toggle nav-link-hover {{ request()->routeIs('shop.*') || request()->routeIs('products.*') || request()->routeIs('categories.*') ? 'active-nav' : '' }}"
                            type="button"
                            id="shopDropdown"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="font-weight: 500; border: none; background: none;">
                        {{ __('Shop') }}
                        <i class="fas fa-chevron-down {{ $isRtl ? 'me-1' : 'ms-1' }}" style="font-size: 0.7rem;"></i>
                    </button>
                    
                    <!-- Shop Dropdown Menu -->
                    <div class="dropdown-menu shadow-lg border-0 rounded-3 p-3" 
                         aria-labelledby="shopDropdown"
                         style="width: 320px; min-width: 320px; margin-top: 0.5rem;"
                         dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        
                        <!-- Header -->
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="fw-bold text-gray-900 mb-1">{{ __('Browse Collections') }}</h6>
                            <p class="text-gray-500 mb-0 small">{{ __('Handpicked artisan products') }}</p>
                        </div>
                        
                        <!-- Categories List -->
                        <div class="mb-3">
                            @foreach(\App\Models\Category::withCount('products')->get() ?? [] as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" 
                               class="dropdown-item d-flex align-items-center py-2.5 px-3 rounded-2 mb-1 category-item {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <div class="d-flex align-items-center justify-content-center rounded-2 {{ $isRtl ? 'ms-3' : 'me-3' }}" 
                                     style="width: 36px; height: 36px; background: linear-gradient(135deg, #fef3c7, #fde68a);">
                                    <i class="fas fa-box text-amber-600" style="font-size: 0.9rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                        <div class="fw-medium">{{ $category->name[app()->getLocale()] ?? $category->name['en'] ??   $category->name }}</div>
                                    <div class="text-gray-500 small">{{ $category->products_count ?? 0 }} items</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        
                        <!-- View All Link -->
                        <div class="pt-2 border-top">
                            <a href="{{ route('products.index') }}" 
                               class="btn btn-amber-50 text-amber-700 fw-medium w-100 d-flex align-items-center justify-content-center py-2.5 rounded-2 hover-amber {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                <i class="fas fa-boxes {{ $isRtl ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('View All Products') }}
                                <i class="fas fa-arrow-right {{ $isRtl ? 'me-2 rotate-180' : 'ms-2' }} small"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('services.index') }}" 
                   class="nav-link px-4 py-2.5 rounded-3 d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('services.*') ? 'active-nav' : 'nav-link-hover' }}"
                   style="font-weight: 500;">
                    {{ __('Services') }}
                </a>
                
                <a href="{{ route('about') }}" 
                   class="nav-link px-4 py-2.5 rounded-3 d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('about') ? 'active-nav' : 'nav-link-hover' }}"
                   style="font-weight: 500;">
                    {{ __('About') }}
                </a>
                
                <a href="{{ route('contact') }}" 
                   class="nav-link px-4 py-2.5 rounded-3 d-flex align-items-center gap-2 {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('contact') ? 'active-nav' : 'nav-link-hover' }}"
                   style="font-weight: 500;">
                    {{ __('Contact') }}
                </a>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center gap-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                
                <!-- Search Button -->
                {{-- <button class="btn btn-outline-light p-2 rounded-circle nav-icon-hover"
                        onclick="toggleSearch()"
                        style="border: none; color: #4b5563;">
                    <i class="fas fa-search" style="font-size: 1.1rem;"></i>
                </button> --}}

                <!-- Language Switcher -->
                @if(count(LaravelLocalization::getSupportedLocales()) > 1)
                <div class="dropdown">
                    <button class="btn btn-outline-light p-2 rounded-circle nav-icon-hover dropdown-toggle" 
                            type="button"
                            data-bs-toggle="dropdown"
                            style="border: none; color: #4b5563; font-weight: 500;">
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}" 
                               class="dropdown-item d-flex align-items-center py-2 px-3 {{ $isRtl ? 'flex-row-reverse' : '' }} {{ app()->getLocale() == $localeCode ? 'active' : '' }}">
                                <span class="{{ $isRtl ? 'ms-3' : 'me-3' }}">{{ $localeCode == 'en' ? '🇺🇸' : '🇲🇦' }}</span>
                                {{ $properties['native'] }}
                                @if(app()->getLocale() == $localeCode)
                                    <i class="fas fa-check text-amber-600 {{ $isRtl ? 'me-auto' : 'ms-auto' }}"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Wishlist -->
                <a href="{{ route('wishlist') }}" 
                   class="btn btn-outline-light p-2 rounded-circle nav-icon-hover position-relative"
                   style="border: none; color: #4b5563;">
                    <i class="fas fa-heart" style="font-size: 1.1rem;"></i>
                    @auth
                        @if(auth()->user()->wishlistItems()->count() > 0)
                            <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-amber-600 text-white" 
                                  style="font-size: 0.6rem; padding: 2px 4px;">
                                {{ auth()->user()->wishlistItems()->count() }}
                            </span>
                        @endif
                    @endauth
                </a>

                <!-- Cart (Link) -->
                <a href="{{ route('cart.index') }}" 
                   class="btn btn-outline-light p-2 rounded-circle nav-icon-hover position-relative"
                   style="border: none; color: #4b5563;">
                    <i class="fas fa-shopping-bag" style="font-size: 1.1rem;"></i>
                    @if(Cart::count() > 0)
                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-amber-600 text-white" 
                              style="font-size: 0.6rem; padding: 2px 4px;">
                            {{ Cart::count() }}
                        </span>
                    @endif
                </a>

                <!-- User Account -->
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-light p-2 rounded-circle nav-icon-hover dropdown-toggle" 
                                type="button"
                                data-bs-toggle="dropdown"
                                style="border: none; color: #4b5563;">
                            <i class="fas fa-user" style="font-size: 1.1rem;"></i>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-3" style="width: 280px;" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                            <!-- User Info -->
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center gap-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; background: linear-gradient(135deg, #fef3c7, #fde68a);">
                                        <span class="fw-bold text-amber-700" style="font-size: 1rem;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-gray-900">{{ Auth::user()->name }}</div>
                                        <div class="text-gray-500 small">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Menu Items -->
                            <div>
                                <a href="{{ route('profile') }}" 
                                   class="dropdown-item d-flex align-items-center py-2.5 px-3 rounded-2 mb-1 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                    <i class="fas fa-user-circle text-gray-500 {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                                    {{ __('My Profile') }}
                                </a>
                                
                               
                                
                                <a href="{{ route('wishlist') }}" 
                                   class="dropdown-item d-flex align-items-center py-2.5 px-3 rounded-2 mb-1 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                    <i class="fas fa-heart text-gray-500 {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                                    {{ __('Wishlist') }}
                                </a>
                                
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                                    <div class="my-2 py-2 border-top border-bottom">
                                        <a href="{{ route('admin.dashboard') }}" 
                                           class="dropdown-item d-flex align-items-center py-2.5 px-3 rounded-2 bg-amber-50 text-amber-700 fw-medium {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                            <i class="fas fa-tachometer-alt {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                                            {{ __('Admin Dashboard') }}
                                            <i class="fas fa-external-link-alt {{ $isRtl ? 'me-auto' : 'ms-auto' }} small opacity-50"></i>
                                        </a>
                                    </div>
                                @endif
                                
                                <div class="pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="dropdown-item d-flex align-items-center py-2.5 px-3 rounded-2 text-danger hover-danger w-100 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                                            <i class="fas fa-sign-out-alt {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                                            {{ __('Log Out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="btn btn-amber-600 text-white px-4 py-2 rounded-3 fw-medium hover-amber"
                       style="background: linear-gradient(135deg, #d97706, #b45309); border: none;">
                        {{ __('Login') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Search Overlay -->
    <div class="search-overlay" id="searchOverlay" style="display: none;" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="search-container">
            <div class="container position-relative">
                <div class="search-box">
                    <input type="text" 
                           placeholder="{{ __('Search for products, categories...') }}" 
                           class="search-input"
                           id="searchInput">
                    <button class="search-close" onclick="toggleSearch()">
                        <i class="fas fa-times"></i>
                    </button>
                    <button class="search-submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="search-suggestions">
                    <div class="text-gray-500 mb-2 small">{{ __('Popular searches:') }}</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(['Pottery', 'Jewelry', 'Textiles', 'Woodwork', 'Ceramics'] as $term)
                            <button class="search-tag" onclick="performSearch('{{ $term }}')">
                                {{ $term }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu" style="display: none;" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
        <div class="mobile-menu-header">
            <div class="d-flex align-items-center justify-content-between {{ $isRtl ? 'flex-row-reverse' : '' }}">
                <div class="d-flex align-items-center gap-3 {{ $isRtl ? 'flex-row-reverse' : '' }}">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" 
                         style="width: 40px; height: 40px; background: linear-gradient(135deg, #d97706, #92400e);">
                        <span class="text-white fw-bold">AS</span>
                    </div>
                    <div>
                        <div class="fw-bold text-gray-900">ArtisanStore</div>
                        <div class="text-gray-500 small">{{ __('Menu') }}</div>
                    </div>
                </div>
                <button class="btn btn-outline-light p-2" onclick="toggleMobileMenu()"
                        style="border: none; color: #4b5563;">
                    <i class="fas fa-times" style="font-size: 1.2rem;"></i>
                </button>
            </div>
        </div>
        
        <div class="mobile-menu-content">
            <!-- Navigation Links -->
            <div class="mobile-nav-section">
                <a href="{{ route('home') }}" class="mobile-nav-item {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                    {{ __('Home') }}
                </a>
                
                <a href="{{ route('products.index') }}" class="mobile-nav-item {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-store {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                    {{ __('All Products') }}
                </a>
                
                <!-- Mobile Categories -->
                <div class="mobile-nav-categories">
                    <div class="mobile-nav-header {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <i class="fas fa-tags {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                        {{ __('Categories') }}
                    </div>
                    <div class="mobile-categories-list">
                        @foreach($categories ?? [] as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" class="mobile-category-item">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <a href="{{ route('services.index') }}" class="mobile-nav-item {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                    {{ __('Services') }}
                </a>
                
                <a href="{{ route('about') }}" class="mobile-nav-item {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                    {{ __('About') }}
                </a>
                
                <a href="{{ route('contact') }}" class="mobile-nav-item {{ $isRtl ? 'flex-row-reverse' : '' }} {{ request()->routeIs('contact') ? 'active' : '' }}">
                    <i class="fas fa-envelope {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                    {{ __('Contact') }}
                </a>
            </div>
            
            <!-- Account Section -->
            <div class="mobile-account-section">
                <div class="mobile-section-title">{{ __('Account') }}</div>
                
                @auth
                    <div class="mobile-user-info">
                        <div class="fw-bold text-gray-900">{{ Auth::user()->name }}</div>
                        <div class="text-gray-500 small">{{ Auth::user()->email }}</div>
                    </div>
                    
                    <a href="{{ route('profile') }}" class="mobile-account-item {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <i class="fas fa-user-circle {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                        {{ __('My Profile') }}
                    </a>
                    
                    <a href="{{ route('orders.index') }}" class="mobile-account-item {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <i class="fas fa-shopping-bag {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                        {{ __('My Orders') }}
                    </a>
                    
                    <a href="{{ route('wishlist') }}" class="mobile-account-item {{ $isRtl ? 'flex-row-reverse' : '' }}">
                        <i class="fas fa-heart {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                        {{ __('Wishlist') }}
                    </a>
                    
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                        <a href="{{ route('admin.dashboard') }}" class="mobile-account-item text-amber-600 fw-medium {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-tachometer-alt {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                            {{ __('Admin Dashboard') }}
                        </a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-logout-btn {{ $isRtl ? 'flex-row-reverse' : '' }}">
                            <i class="fas fa-sign-out-alt {{ $isRtl ? 'ms-3' : 'me-3' }}" style="width: 20px;"></i>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="mobile-login-btn">
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="mobile-register-btn">
                        {{ __('Register') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
/* Custom Styles for Enhanced Navigation */

/* Logo hover effect */
.logo-hover:hover .position-absolute {
    opacity: 1 !important;
}

/* Navigation link hover effects */
.nav-link-hover {
    color: #4b5563 !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.nav-link-hover:hover {
    color: #d97706 !important;
    background-color: rgba(254, 243, 199, 0.5) !important;
    transform: translateY(-1px);
}

.active-nav {
    color: #d97706 !important;
    background-color: rgba(254, 243, 199, 0.8) !important;
    font-weight: 600 !important;
}

/* Icon hover effects */
.nav-icon-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.nav-icon-hover:hover {
    color: #d97706 !important;
    background-color: rgba(254, 243, 199, 0.5) !important;
    transform: translateY(-1px);
}

/* Button hover effects */
.hover-amber {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.hover-amber:hover {
    background: linear-gradient(135deg, #b45309, #92400e) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(180, 83, 9, 0.3) !important;
}

/* Dropdown enhancements */
.dropdown-menu {
    animation: dropdownSlideIn 0.2s ease-out !important;
}

@keyframes dropdownSlideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Category item hover */
.category-item {
    transition: all 0.2s ease !important;
}

.category-item:hover {
    background-color: rgba(254, 243, 199, 0.5) !important;
    color: #d97706 !important;
}

.category-item:hover .rounded-2 {
    background: linear-gradient(135deg, #fde68a, #fcd34d) !important;
}

.category-item:hover .fa-chevron-right {
    color: #d97706 !important;
}

/* Danger hover */
.hover-danger:hover {
    background-color: rgba(254, 226, 226, 0.8) !important;
    color: #dc2626 !important;
}

/* Search Overlay */
.search-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 1040;
    animation: fadeIn 0.3s ease;
}

.search-container {
    position: absolute;
    top: 100px;
    left: 0;
    right: 0;
}

.search-box {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.search-input {
    width: 100%;
    padding: 1.5rem 4rem 1.5rem 1.5rem;
    font-size: 1.1rem;
    border: none;
    border-radius: 12px;
    background: white;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.search-input:focus {
    outline: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.search-close {
    position: absolute;
    right: 4.5rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #6b7280;
    font-size: 1.2rem;
    cursor: pointer;
}

.search-submit {
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #d97706;
    font-size: 1.2rem;
    cursor: pointer;
}

.search-suggestions {
    max-width: 800px;
    margin: 2rem auto 0;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.search-tag {
    padding: 0.5rem 1rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 50px;
    color: #4b5563;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.search-tag:hover {
    background: #fef3c7;
    border-color: #d97706;
    color: #d97706;
}

/* Mobile Menu */
.mobile-menu {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: white;
    z-index: 1050;
    overflow-y: auto;
    animation: slideInLeft 0.3s ease;
}

.mobile-menu-header {
    padding: 1.5rem;
    border-bottom: 1px solid #f3f4f6;
}

.mobile-menu-content {
    padding: 1rem;
}

.mobile-nav-section {
    margin-bottom: 2rem;
}

.mobile-nav-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    color: #4b5563;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.mobile-nav-item:hover,
.mobile-nav-item.active {
    background-color: #fef3c7;
    color: #d97706;
}

.mobile-nav-categories {
    margin: 1rem 0;
}

.mobile-nav-header {
    display: flex;
    align-items: center;
    padding: 1rem;
    color: #4b5563;
    font-weight: 500;
}

.mobile-categories-list {
    padding-left: 3rem;
}

.mobile-category-item {
    display: block;
    padding: 0.75rem 1rem;
    color: #6b7280;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 0.25rem;
    transition: all 0.2s ease;
}

.mobile-category-item:hover {
    background-color: #f3f4f6;
    color: #d97706;
}

.mobile-account-section {
    border-top: 1px solid #f3f4f6;
    padding-top: 2rem;
}

.mobile-section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    padding: 0 1rem;
}

.mobile-user-info {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.mobile-account-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    color: #4b5563;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.mobile-account-item:hover {
    background-color: #f3f4f6;
}

.mobile-logout-btn {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 1rem;
    background: none;
    border: none;
    color: #dc2626;
    text-align: left;
    border-radius: 8px;
    margin-top: 1rem;
    transition: all 0.2s ease;
}

.mobile-logout-btn:hover {
    background-color: #fee2e2;
}

.mobile-login-btn {
    display: block;
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #d97706, #b45309);
    color: white;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.mobile-register-btn {
    display: block;
    width: 100%;
    padding: 1rem;
    background: white;
    color: #d97706;
    text-align: center;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    border: 2px solid #d97706;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInLeft {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}

/* Custom colors */
.bg-amber-50 {
    background-color: #fffbeb;
}

.bg-amber-100 {
    background-color: #fef3c7;
}

.text-amber-600 {
    color: #d97706;
}

.text-amber-700 {
    color: #b45309;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .search-input {
        padding: 1rem 3.5rem 1rem 1rem;
        font-size: 1rem;
    }
    
    .search-close {
        right: 3.5rem;
    }
    
    .search-submit {
        right: 1rem;
    }
}
</style>

<script>
// Toggle functions
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
        document.body.style.overflow = 'hidden';
    } else {
        menu.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function toggleSearch() {
    const overlay = document.getElementById('searchOverlay');
    if (overlay.style.display === 'none' || overlay.style.display === '') {
        overlay.style.display = 'block';
        document.getElementById('searchInput').focus();
        document.body.style.overflow = 'hidden';
    } else {
        overlay.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function performSearch(query) {
    if (query && query.trim()) {
        window.location.href = '{{ route("products.index") }}?search=' + encodeURIComponent(query);
        toggleSearch();
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuBtn = document.querySelector('[onclick="toggleMobileMenu()"]');
    
    if (mobileMenu.style.display === 'block' && 
        !mobileMenu.contains(event.target) && 
        !mobileMenuBtn.contains(event.target)) {
        toggleMobileMenu();
    }
});

// Close search when clicking outside
document.addEventListener('click', function(event) {
    const searchOverlay = document.getElementById('searchOverlay');
    const searchBox = document.querySelector('.search-box');
    
    if (searchOverlay.style.display === 'block' && 
        !searchBox.contains(event.target) && 
        event.target.className !== 'search-tag') {
        toggleSearch();
    }
});

// Handle search input
document.getElementById('searchInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performSearch(this.value);
    }
});
</script>