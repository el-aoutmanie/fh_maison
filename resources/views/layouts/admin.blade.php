<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'NounieStore') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Scripts -->
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="min-vh-100" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        @include('components.admin.sidebar')

        <!-- Main Content -->
        <div class="admin-content" :class="{ 'content-expanded': !sidebarOpen }">
            <!-- Top Navigation -->
            @include('components.admin.navbar')

            <!-- Page Content -->
            <main class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <style>
        /* Main content area */
        .admin-content {
            transition: margin 0.3s ease;
        }
        
        /* LTR styles */
        [dir="ltr"] .admin-content {
            margin-left: 256px;
        }
        
        [dir="ltr"] .admin-content.content-expanded {
            margin-left: 0;
        }
        
        /* RTL styles */
        [dir="rtl"] .admin-content {
            margin-right: 256px;
        }
        
        [dir="rtl"] .admin-content.content-expanded {
            margin-right: 0;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .admin-content,
            [dir="ltr"] .admin-content,
            [dir="rtl"] .admin-content {
                margin-left: 0;
                margin-right: 0;
            }
        }
    </style>
</body>
</html>
