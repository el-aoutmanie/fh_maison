<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NounieStore') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-linen">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                        <div class="rounded-circle bg-gradient shadow-lg d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: linear-gradient(135deg, #D4A574, #9C6644);">
                            <span class="text-white fw-bold fs-2" style="font-family: serif;">A</span>
                        </div>
                    </div>
                    <h2 class="fw-bold text-charcoal mb-0" style="font-family: serif;">
                        Artisan<span class="text-terracotta">Store</span>
                    </h2>
                    <p class="small text-stone text-uppercase" style="letter-spacing: 0.1em;">{{ __('Handcrafted Excellence') }}</p>
                </a>
            </div>

            <div class="card border-0 shadow-lg rounded-4" style="width: 100%; max-width: 450px;">
                <div class="card-body p-4 p-sm-5">
                    {{ $slot }}
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    © {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}
                </p>
            </div>
        </div>
    </body>
</html>
