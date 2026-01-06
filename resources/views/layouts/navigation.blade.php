<nav x-data="{ open: true, cartOpen: false, searchOpen: false, langOpen: false }" 
     class="bg-white border-b border-sand-200 shadow-sm sticky top-0 z-50 transition-all duration-300">
    
    <!-- Top Announcement Bar -->
    <div class="bg-terracotta-500 text-white text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-2">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse animate-pulse-slow">
                        <i class="fas fa-gift text-xs"></i>
                        <span>{{ __('Free shipping on orders over $50') }}</span>
                    </div>
                    <div class="hidden md:flex items-center space-x-2 rtl:space-x-reverse">
                        <i class="fas fa-star text-xs"></i>
                        <span>{{ __('Handcrafted with love') }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <a href="{{ route('contact') }}" class="hover:text-clay-200 transition-colors">
                        {{ __('Contact Us') }}
                    </a>
                    <div class="hidden lg:flex items-center space-x-4 rtl:space-x-reverse">
                        <a href="{{ route('track.order') }}" class="hover:text-clay-200 transition-colors">
                            {{ __('Track Order') }}
                        </a>
                        <span class="text-clay-300">|</span>
                        <a href="{{ route('blog') }}" class="hover:text-clay-200 transition-colors">
                            {{ __('Blog') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Primary Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Logo Section -->
            <div class="flex items-center space-x-8 rtl:space-x-reverse">
                <!-- Mobile Menu Button -->
                <button @click="open = !open" 
                        class="lg:hidden p-2 rounded-md text-charcoal-600 hover:text-terracotta-500 hover:bg-linen-100 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" 
                              class="inline-flex" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" 
                              class="hidden" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse group">
                    <div class="relative">
                        <div class="w-12 h-12 bg-gradient-to-br from-clay-500 to-terracotta-600 rounded-full flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                            <span class="text-white font-serif font-bold text-2xl">A</span>
                        </div>
                        <div class="absolute -inset-1 bg-clay-300 rounded-full opacity-0 group-hover:opacity-20 blur-sm transition-opacity duration-300"></div>
                    </div>
                    <div>
                        <div class="text-2xl font-serif font-bold text-charcoal-800 leading-tight">
                            Artisan<span class="text-terracotta-600">Store</span>
                        </div>
                        <div class="text-xs text-stone-500 font-medium tracking-wider">
                            {{ __('Handcrafted Excellence') }}
                        </div>
                    </div>
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center space-x-8 rtl:space-x-reverse">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="nav-item">
                    <i class="fas fa-home mr-2 rtl:ml-2 rtl:mr-0"></i>
                    {{ __('Home') }}
                </x-nav-link>
                
                <x-nav-dropdown :active="request()->routeIs('shop.*')" class="nav-item">
                    <x-slot name="trigger">
                        <i class="fas fa-store mr-2 rtl:ml-2 rtl:mr-0"></i>
                        {{ __('Shop') }}
                        <i class="fas fa-chevron-down ml-1 rtl:mr-1 rtl:ml-0 text-xs"></i>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('shop.all')">
                            <i class="fas fa-boxes mr-2 rtl:ml-2 rtl:mr-0"></i>
                            {{ __('All Products') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('shop.featured')">
                            <i class="fas fa-star mr-2 rtl:ml-2 rtl:mr-0"></i>
                            {{ __('Featured Products') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('shop.new')">
                            <i class="fas fa-leaf mr-2 rtl:ml-2 rtl:mr-0"></i>
                            {{ __('New Arrivals') }}
                        </x-dropdown-link>
                        <div class="border-t border-sand-200 my-2"></div>
                        @foreach($categories->take(5) as $category)
                            <x-dropdown-link :href="route('shop.category', $category->slug)">
                                <i class="fas fa-tag mr-2 rtl:ml-2 rtl:mr-0 text-terracotta-400"></i>
                                {{ $category->name }}
                            </x-dropdown-link>
                        @endforeach
                    </x-slot>
                </x-nav-dropdown>
                
                <x-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')" class="nav-item">
                    <i class="fas fa-hands-helping mr-2 rtl:ml-2 rtl:mr-0"></i>
                    {{ __('Services') }}
                </x-nav-link>
                
                <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="nav-item">
                    <i class="fas fa-info-circle mr-2 rtl:ml-2 rtl:mr-0"></i>
                    {{ __('About') }}
                </x-nav-link>
                
                <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="nav-item">
                    <i class="fas fa-envelope mr-2 rtl:ml-2 rtl:mr-0"></i>
                    {{ __('Contact') }}
                </x-nav-link>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                
                <!-- Search Button -->
                <button @click="searchOpen = !searchOpen" 
                        class="p-2 rounded-full text-charcoal-600 hover:text-terracotta-500 hover:bg-linen-100 transition-colors relative group">
                    <i class="fas fa-search text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-terracotta-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></span>
                </button>

                <!-- Language Switcher -->
                <div class="relative" x-data="{ langOpen: false }">
                    <button @click="langOpen = !langOpen" 
                            class="p-2 rounded-full text-charcoal-600 hover:text-terracotta-500 hover:bg-linen-100 transition-colors flex items-center space-x-1 rtl:space-x-reverse">
                        <span class="fi fi-{{ app()->getLocale() == 'en' ? 'us' : app()->getLocale() }}"></span>
                        <span class="text-sm font-medium uppercase">{{ app()->getLocale() }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    
                    <div x-show="langOpen" 
                         @click.away="langOpen = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 rtl:right-auto rtl:left-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-sand-200 py-2 z-50">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}" 
                               class="flex items-center px-4 py-2 text-sm text-charcoal-700 hover:bg-linen-100 hover:text-terracotta-600 transition-colors {{ app()->getLocale() == $localeCode ? 'bg-linen-50 text-terracotta-600' : '' }}">
                                <span class="fi fi-{{ $localeCode == 'en' ? 'us' : $localeCode }} mr-3 rtl:ml-3 rtl:mr-0"></span>
                                {{ $properties['native'] }}
                                @if(app()->getLocale() == $localeCode)
                                    <i class="fas fa-check ml-auto rtl:mr-auto rtl:ml-0 text-terracotta-500"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- User Account -->
                @auth
                    <div class="relative" x-data="{ userOpen: false }">
                        <button @click="userOpen = !userOpen" 
                                class="p-2 rounded-full text-charcoal-600 hover:text-terracotta-500 hover:bg-linen-100 transition-colors relative group">
                            <i class="fas fa-user text-lg"></i>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-terracotta-500 rounded-full animate-pulse"></span>
                            @endif
                        </button>
                        
                        <div x-show="userOpen" 
                             @click.away="userOpen = false" 
                             x-transition
                             class="absolute right-0 rtl:right-auto rtl:left-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-sand-200 py-2 z-50">
                            <div class="px-4 py-3 border-b border-sand-100">
                                <div class="font-medium text-charcoal-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-stone-500">{{ Auth::user()->email }}</div>
                            </div>
                            
                            <div class="py-2">
                                <x-dropdown-link :href="route('profile')" class="px-4">
                                    <i class="fas fa-user-circle mr-3 rtl:ml-3 rtl:mr-0"></i>
                                    {{ __('My Profile') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('orders.index')" class="px-4">
                                    <i class="fas fa-shopping-bag mr-3 rtl:ml-3 rtl:mr-0"></i>
                                    {{ __('My Orders') }}
                                    @if(auth()->user()->pendingOrders()->count() > 0)
                                        <span class="ml-auto rtl:mr-auto rtl:ml-0 bg-terracotta-100 text-terracotta-600 text-xs px-2 py-0.5 rounded-full">
                                            {{ auth()->user()->pendingOrders()->count() }}
                                        </span>
                                    @endif
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('wishlist')" class="px-4">
                                    <i class="fas fa-heart mr-3 rtl:ml-3 rtl:mr-0"></i>
                                    {{ __('Wishlist') }}
                                </x-dropdown-link>
                                
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                                    <div class="border-t border-sand-100 my-2"></div>
                                    <x-dropdown-link :href="route('admin.dashboard')" class="px-4 text-terracotta-600 font-semibold">
                                        <i class="fas fa-tachometer-alt mr-3 rtl:ml-3 rtl:mr-0"></i>
                                        {{ __('Admin Dashboard') }}
                                    </x-dropdown-link>
                                @endif
                                
                                <div class="border-t border-sand-100 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-stone-600 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-3 rtl:ml-3 rtl:mr-0"></i>
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="p-2 rounded-full text-charcoal-600 hover:text-terracotta-500 hover:bg-linen-100 transition-colors">
                        <i class="fas fa-user text-lg"></i>
                    </a>
                @endauth

                <!-- Wishlist -->
                <a href="{{ route('wishlist') }}" 
                   class="p-2 rounded-full text-charcoal-600 hover:text-terracotta-500 hover:bg-linen-100 transition-colors relative group">
                    <i class="fas fa-heart text-lg"></i>
                    @if(auth()->check() && auth()->user()->wishlistItems()->count() > 0)
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-terracotta-500 text-white text-xs rounded-full flex items-center justify-center">
                            {{ auth()->user()->wishlistItems()->count() }}
                        </span>
                    @endif
                </a>

                <!-- Cart -->
                <button @click="cartOpen = !cartOpen" 
                        class="p-2 rounded-full text-charcoal-600 hover:text-terracotta-500 hover:bg-linen-100 transition-colors relative group">
                    <i class="fas fa-shopping-bag text-lg"></i>
                    @if(Cart::count() > 0)
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-terracotta-500 text-white text-xs rounded-full flex items-center justify-center animate-bounce-slow">
                            {{ Cart::count() }}
                        </span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Search Overlay -->
    <div x-show="searchOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-50" 
         style="display: none;"
         @click="searchOpen = false">
    </div>
    
    <div x-show="searchOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform -translate-y-10 opacity-0"
         x-transition:enter-end="transform translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform translate-y-0 opacity-100"
         x-transition:leave-end="transform -translate-y-10 opacity-0"
         class="fixed top-20 left-1/2 transform -translate-x-1/2 w-full max-w-3xl z-50 px-4"
         style="display: none;"
         @click.away="searchOpen = false">
        <div class="bg-white rounded-lg shadow-2xl border border-sand-200 overflow-hidden">
            <div class="relative">
                <input type="text" 
                       placeholder="{{ __('Search for handcrafted products...') }}" 
                       class="w-full px-6 py-4 text-lg focus:outline-none border-0"
                       x-ref="searchInput"
                       @keyup.enter="performSearch($event.target.value)"
                       autofocus>
                <button class="absolute right-4 rtl:right-auto rtl:left-4 top-1/2 transform -translate-y-1/2 text-terracotta-500">
                    <i class="fas fa-search text-xl"></i>
                </button>
            </div>
            <div class="px-6 py-4 bg-linen-50 border-t border-sand-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-stone-500">{{ __('Popular searches:') }}</span>
                    <div class="flex space-x-2 rtl:space-x-reverse">
                        @foreach(['Pottery', 'Jewelry', 'Textiles', 'Woodwork'] as $term)
                            <button @click="performSearch('{{ $term }}')" 
                                    class="text-sm px-3 py-1 bg-white border border-sand-200 rounded-full hover:bg-terracotta-50 hover:border-terracotta-200 hover:text-terracotta-600 transition-colors">
                                {{ $term }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform -translate-x-full"
         class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40"
         style="display: none;"
         @click="open = false">
    </div>
    
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform -translate-x-full"
         x-transition:enter-end="transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform translate-x-0"
         x-transition:leave-end="transform -translate-x-full"
         class="lg:hidden fixed inset-y-0 left-0 w-80 bg-white shadow-2xl z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="p-6">
            <!-- Mobile Logo -->
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="w-10 h-10 bg-gradient-to-br from-clay-500 to-terracotta-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-serif font-bold text-xl">A</span>
                    </div>
                    <div>
                        <div class="text-xl font-serif font-bold text-charcoal-800">ArtisanStore</div>
                        <div class="text-xs text-stone-500">{{ __('Handcrafted') }}</div>
                    </div>
                </a>
                <button @click="open = false" class="p-2 rounded-full hover:bg-linen-100">
                    <i class="fas fa-times text-lg text-charcoal-600"></i>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="mobile-nav-item">
                    <i class="fas fa-home w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                    {{ __('Home') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('shop.all')" :active="request()->routeIs('shop.*')" class="mobile-nav-item">
                    <i class="fas fa-store w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                    {{ __('Shop All') }}
                </x-responsive-nav-link>
                
                <!-- Mobile Categories Dropdown -->
                <div x-data="{ categoriesOpen: false }" class="mobile-nav-item">
                    <button @click="categoriesOpen = !categoriesOpen" 
                            class="flex items-center justify-between w-full py-3 text-left">
                        <div class="flex items-center">
                            <i class="fas fa-tags w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                            <span>{{ __('Categories') }}</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform" 
                           :class="{ 'rotate-180': categoriesOpen }"></i>
                    </button>
                    <div x-show="categoriesOpen" class="mt-2 ml-9 rtl:ml-0 rtl:mr-9 space-y-2">
                        @foreach($categories->take(8) as $category)
                            <a href="{{ route('shop.category', $category->slug) }}" 
                               class="block py-2 text-sm text-stone-600 hover:text-terracotta-500">
                                {{ $category->name }}
                            </a>
                        @endforeach
                        <a href="{{ route('categories.index') }}" 
                           class="block py-2 text-sm font-medium text-terracotta-600">
                            {{ __('View All Categories') }} →
                        </a>
                    </div>
                </div>
                
                <x-responsive-nav-link :href="route('services.index')" :active="request()->routeIs('services.*')" class="mobile-nav-item">
                    <i class="fas fa-hands-helping w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                    {{ __('Services') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')" class="mobile-nav-item">
                    <i class="fas fa-info-circle w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                    {{ __('About Us') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="mobile-nav-item">
                    <i class="fas fa-envelope w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                    {{ __('Contact') }}
                </x-responsive-nav-link>
            </div>

            <!-- Mobile Account Section -->
            <div class="mt-8 pt-8 border-t border-sand-200">
                <h3 class="text-sm font-semibold text-stone-500 uppercase tracking-wider mb-4">
                    {{ __('Account') }}
                </h3>
                
                @auth
                    <div class="space-y-2">
                        <div class="px-4 py-3 bg-linen-50 rounded-lg">
                            <div class="font-medium text-charcoal-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-stone-500">{{ Auth::user()->email }}</div>
                        </div>
                        
                        <a href="{{ route('profile') }}" class="mobile-nav-item">
                            <i class="fas fa-user-circle w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                            {{ __('My Profile') }}
                        </a>
                        
                        <a href="{{ route('orders.index') }}" class="mobile-nav-item">
                            <i class="fas fa-shopping-bag w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                            {{ __('My Orders') }}
                        </a>
                        
                        <a href="{{ route('wishlist') }}" class="mobile-nav-item">
                            <i class="fas fa-heart w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                            {{ __('Wishlist') }}
                        </a>
                        
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item text-terracotta-600 font-semibold">
                                <i class="fas fa-tachometer-alt w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                                {{ __('Admin Dashboard') }}
                            </a>
                        @endif
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full mobile-nav-item text-red-600">
                                <i class="fas fa-sign-out-alt w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" class="mobile-nav-item">
                            <i class="fas fa-sign-in-alt w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                            {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="mobile-nav-item">
                            <i class="fas fa-user-plus w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                            {{ __('Register') }}
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Contact Info -->
            <div class="mt-8 pt-8 border-t border-sand-200">
                <div class="space-y-3">
                    <a href="tel:+1234567890" class="flex items-center text-stone-600 hover:text-terracotta-500">
                        <i class="fas fa-phone w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                        <span>+1 (234) 567-890</span>
                    </a>
                    <a href="mailto:info@artisanstore.com" class="flex items-center text-stone-600 hover:text-terracotta-500">
                        <i class="fas fa-envelope w-6 text-center mr-3 rtl:ml-3 rtl:mr-0"></i>
                        <span>info@artisanstore.com</span>
                    </a>
                </div>
                
                <div class="mt-6 flex space-x-4 rtl:space-x-reverse">
                    <a href="#" class="w-10 h-10 bg-linen-100 rounded-full flex items-center justify-center text-charcoal-600 hover:bg-terracotta-500 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-linen-100 rounded-full flex items-center justify-center text-charcoal-600 hover:bg-terracotta-500 hover:text-white transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-linen-100 rounded-full flex items-center justify-center text-charcoal-600 hover:bg-terracotta-500 hover:text-white transition-colors">
                        <i class="fab fa-pinterest"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Cart Sidebar -->
<div x-show="cartOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 z-50"
     style="display: none;"
     @click="cartOpen = false">
</div>

<div x-show="cartOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform translate-x-full"
     x-transition:enter-end="transform translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="transform translate-x-0"
     x-transition:leave-end="transform translate-x-full"
     class="fixed inset-y-0 right-0 w-full md:w-96 bg-white shadow-2xl z-50 overflow-y-auto"
     style="display: none;"
     @click.away="cartOpen = false">
    
    <div class="p-6 h-full flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-serif font-bold text-charcoal-800">
                {{ __('Your Cart') }}
                <span class="text-terracotta-500">({{ Cart::count() }})</span>
            </h3>
            <button @click="cartOpen = false" class="p-2 rounded-full hover:bg-linen-100">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        @if(Cart::count() > 0)
            <div class="flex-1 overflow-y-auto">
                <!-- Cart items will be loaded here -->
                <div class="space-y-4">
                    <!-- Example cart item -->
                    <div class="flex items-center space-x-4 rtl:space-x-reverse p-3 bg-linen-50 rounded-lg">
                        <div class="w-16 h-16 bg-sand-100 rounded-md overflow-hidden">
                            <img src="https://via.placeholder.com/64" alt="Product" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-charcoal-800">Handmade Pottery Mug</h4>
                            <p class="text-sm text-stone-500">Size: Medium</p>
                            <div class="flex items-center justify-between mt-1">
                                <span class="font-bold text-terracotta-600">$24.99</span>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <button class="w-6 h-6 rounded-full bg-sand-100 flex items-center justify-center">
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>
                                    <span class="font-medium">1</span>
                                    <button class="w-6 h-6 rounded-full bg-sand-100 flex items-center justify-center">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-sand-200">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-stone-600">{{ __('Subtotal') }}</span>
                        <span class="font-bold text-charcoal-800">$124.95</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-stone-600">{{ __('Shipping') }}</span>
                        <span class="text-green-600">{{ __('Free') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-sand-200">
                        <span>{{ __('Total') }}</span>
                        <span class="text-terracotta-600">$124.95</span>
                    </div>
                </div>
                
                <div class="mt-6 space-y-3">
                    <a href="{{ route('cart.index') }}" 
                       class="block w-full bg-sand-200 hover:bg-clay-500 text-charcoal-800 hover:text-white text-center py-3 rounded-lg font-medium transition-colors">
                        {{ __('View Cart') }}
                    </a>
                    <a href="{{ route('checkout') }}" 
                       class="block w-full bg-terracotta-500 hover:bg-terracotta-600 text-white text-center py-3 rounded-lg font-medium transition-colors">
                        {{ __('Checkout') }}
                    </a>
                </div>
            </div>
        @else
            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="w-32 h-32 bg-linen-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-shopping-bag text-4xl text-sand-300"></i>
                </div>
                <h4 class="text-xl font-medium text-charcoal-800 mb-2">{{ __('Your cart is empty') }}</h4>
                <p class="text-stone-500 text-center mb-6">{{ __('Add some handcrafted items to get started') }}</p>
                <a href="{{ route('shop.all') }}" 
                   class="bg-terracotta-500 hover:bg-terracotta-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    {{ __('Start Shopping') }}
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function performSearch(query) {
        if (query.trim()) {
            window.location.href = '{{ route("shop.search") }}?q=' + encodeURIComponent(query);
        }
    }
    
    // Initialize cart from localStorage or session
    document.addEventListener('alpine:init', () => {
        Alpine.store('cart', {
            items: @json(Cart::content()),
            count: {{ Cart::count() }},
            total: {{ Cart::total() }},
            
            add(item) {
                // Implementation
            },
            
            update(itemId, quantity) {
                // Implementation
            },
            
            remove(itemId) {
                // Implementation
            }
        });
    });
</script>
@endpush

<style>
    .nav-item {
        @apply px-4 py-2 rounded-lg text-charcoal-700 hover:text-terracotta-600 hover:bg-linen-100 transition-all duration-300 font-medium flex items-center;
    }
    
    .nav-item.active {
        @apply text-terracotta-600 bg-linen-50 font-semibold;
    }
    
    .mobile-nav-item {
        @apply flex items-center py-3 px-4 rounded-lg text-charcoal-700 hover:text-terracotta-600 hover:bg-linen-100 transition-colors;
    }
    
    .mobile-nav-item.active {
        @apply text-terracotta-600 bg-linen-50 font-semibold;
    }
    
    /* Flag icons styling */
    .fi {
        border-radius: 2px;
        box-shadow: 0 0 1px rgba(0,0,0,0.2);
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Custom scrollbar for cart */
    .cart-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    
    .cart-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    
    .cart-scrollbar::-webkit-scrollbar-thumb {
        background: #E6CCB2;
        border-radius: 2px;
    }
    
    .cart-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #D4A574;
    }
</style>