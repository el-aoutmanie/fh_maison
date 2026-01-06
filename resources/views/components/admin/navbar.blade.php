<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container-fluid px-4">
        <!-- Sidebar toggle button -->
        <button @click="sidebarOpen = !sidebarOpen" class="btn btn-link text-dark p-0 me-3" style="border: none;">
            <i class="fas fa-bars fs-5"></i>
        </button>

        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen" class="btn btn-link d-md-none text-dark p-0" style="border: none;">
            <i class="fas fa-bars fs-5"></i>
        </button>
        
        <div class="flex-grow-1"></div>
        
        <!-- Right side -->
        <div class="d-flex align-items-center gap-3">
            <!-- Language Switcher -->
            <div class="dropdown" x-data="{ open: false }">
                <button @click="open = !open" class="btn btn-link text-dark d-flex align-items-center gap-2 text-decoration-none" style="border: none;">
                    <i class="fas fa-globe"></i>
                    <span class="text-uppercase small fw-semibold">{{ app()->getLocale() }}</span>
                </button>
                <div x-show="open" 
                     @click.away="open = false" 
                     x-transition
                     class="dropdown-menu dropdown-menu-{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'start' : 'end' }} show position-absolute shadow-lg border-0 rounded-3 py-2 mt-2"
                     style="min-width: 12rem;">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" 
                           class="dropdown-item d-flex align-items-center px-3 py-2 {{ app()->getLocale() == $localeCode ? 'active' : '' }}">
                            <span class="fi fi-{{ $localeCode == 'en' ? 'us' : $localeCode }} me-2"></span>
                            {{ $properties['native'] }}
                        </a>
                    @endforeach
                </div>
            </div>
            
            <!-- User menu -->
            <div class="dropdown position-relative" x-data="{ open: false }">
                <button @click="open = !open" class="btn btn-link text-dark d-flex align-items-center gap-2 text-decoration-none" style="border: none;">
                    <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <span class="fw-semibold text-primary">{{ substr(auth()->user()->first_name, 0, 1) }}</span>
                    </div>
                </button>
                <div x-show="open" 
                     @click.away="open = false" 
                     x-transition
                     class="dropdown-menu dropdown-menu-{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'start' : 'end' }} shadow-lg border-0 rounded-3 py-2 mt-2"
                     :class="{ 'show': open }"
                     style="min-width: 16rem; position: absolute; right: 0; top: 100%; z-index: 1060;">
                    <div class="px-3 py-2 border-bottom">
                        <p class="fw-semibold mb-1 small">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                        <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('home') }}" class="dropdown-item d-flex align-items-center px-3 py-2">
                        <i class="fas fa-store me-2"></i>
                        {{ __('Visit Store') }}
                    </a>
                    <div class="dropdown-divider my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center px-3 py-2 text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
