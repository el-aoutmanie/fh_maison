<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('NounieStore - Your Trusted Shopping Destination'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @yield('meta')

    <!-- Scripts -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-linen">
    <div x-data="{ searchOpen: false }" class="d-flex flex-column min-vh-100">
        <!-- Navigation -->
        <x-frontend.navigation />

        <!-- Page Content -->
        <main class="flex-grow-1">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <svg width="20" height="20" class="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'ms-2' : 'me-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container mt-4">
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <svg width="20" height="20" class="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'ms-2' : 'me-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="container mt-4">
                    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        <svg width="20" height="20" class="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'ms-2' : 'me-2' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('warning') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <x-frontend.footer />
        
        <!-- Alert Dialog Component -->
        <x-frontend.alert-dialog />
    </div>

    @stack('scripts')
</body>
</html>
