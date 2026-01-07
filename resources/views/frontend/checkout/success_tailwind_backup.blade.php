@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-blue-50 py-16 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-72 h-72 bg-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Success Message with Confetti Effect -->
        <div class="text-center mb-12 animate-fade-in-up">
            <div class="relative inline-block">
                <!-- Animated rings -->
                <div class="absolute inset-0 w-32 h-32 bg-green-400 rounded-full animate-ping opacity-20"></div>
                <div class="absolute inset-2 w-28 h-28 bg-green-300 rounded-full animate-ping opacity-30 animation-delay-300"></div>
                <div class="relative inline-flex items-center justify-center w-32 h-32 bg-gradient-to-br from-green-400 to-emerald-600 rounded-full mb-8 shadow-2xl">
                    <i class="fas fa-check text-white text-5xl animate-bounce-slow"></i>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold bg-gradient-to-r from-emerald-600 via-green-600 to-teal-600 bg-clip-text text-transparent mb-4">
                {{ __('Order Confirmed!') }}
            </h1>
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                {{ __('Thank you for your purchase. Your order has been received and is now being processed with care.') }}
            </p>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden mb-10 border border-white/20 animate-fade-in-up animation-delay-200">
            <!-- Header with Pattern -->
            <div class="relative bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 px-8 py-10 overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                        <rect width="100" height="100" fill="url(#grid)"/>
                    </svg>
                </div>
                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between text-white">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">
                                <i class="fas fa-receipt mr-1"></i> {{ __('Order') }}
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold mb-2">#{{ $order->code }}</h2>
                        <p class="text-emerald-100 text-lg flex items-center gap-2">
                            <i class="far fa-calendar-alt"></i>
                            {{ $order->created_at->format('F j, Y') }}
                            <span class="text-emerald-200">•</span>
                            {{ $order->created_at->format('g:i A') }}
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <div class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-md rounded-2xl shadow-lg border border-white/30">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-400"></span>
                            </span>
                            <span class="font-semibold capitalize text-lg">{{ str_replace('_', ' ', $order->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="p-8 md:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <!-- Customer Info -->
                    <div class="group bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">{{ __('Customer Information') }}</h3>
                        </div>
                        <div class="space-y-3 text-gray-700">
                            <p class="font-semibold text-lg text-gray-900">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                            <p class="flex items-center gap-3">
                                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                </span>
                                {{ $order->customer->email }}
                            </p>
                            <p class="flex items-center gap-3">
                                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-blue-600 text-sm"></i>
                                </span>
                                {{ $order->customer->phone_number }}
                            </p>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="group bg-gradient-to-br from-emerald-50 to-green-50 p-6 rounded-2xl border border-emerald-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-map-marker-alt text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">{{ __('Shipping Address') }}</h3>
                        </div>
                        <div class="text-gray-700 space-y-2">
                            <p class="text-lg">{{ $order->customer->address }}</p>
                            <p class="text-lg">{{ $order->customer->city }}, {{ $order->customer->state }} {{ $order->customer->postal_code }}</p>
                            <p class="text-lg font-medium text-gray-900 flex items-center gap-2">
                                <i class="fas fa-globe-americas text-emerald-600"></i>
                                {{ $order->customer->country }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-shopping-bag text-white text-lg"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ __('Order Items') }}</h3>
                        <span class="ml-auto px-4 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                            {{ $order->items->count() }} {{ __('items') }}
                        </span>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $index => $item)
                        <div class="group flex items-center gap-6 p-5 bg-gradient-to-r from-gray-50 to-white rounded-2xl border border-gray-100 hover:border-purple-200 hover:shadow-lg transition-all duration-300 animate-fade-in-up" style="animation-delay: {{ $index * 100 }}ms">
                            <div class="flex-shrink-0 relative">
                                @if($item->variant->product->images->first())
                                <img src="{{ $item->variant->product->images->first()->url }}" 
                                     alt="{{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? '' }}" 
                                     class="w-20 h-20 md:w-24 md:h-24 object-cover rounded-xl shadow-md group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center shadow-md">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                                @endif
                                <span class="absolute -top-2 -right-2 w-7 h-7 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-lg">
                                    {{ $item->quantity }}
                                </span>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-gray-800 text-lg md:text-xl mb-1 truncate">
                                    {{ $item->variant->product->name[app()->getLocale()] ?? $item->variant->product->name['en'] ?? 'Product' }}
                                </h4>
                                @if($item->variant->name)
                                <p class="text-sm text-gray-500 flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs">{{ __('Variant') }}</span>
                                    {{ $item->variant->name[app()->getLocale()] ?? $item->variant->name['en'] ?? '' }}
                                </p>
                                @endif
                            </div>
                            
                            <div class="text-right">
                                <p class="font-bold text-gray-800 text-xl md:text-2xl">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="flex justify-end">
                    <div class="w-full md:w-96 bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-2xl border border-gray-200">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b border-gray-200">{{ __('Order Summary') }}</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>{{ __('Subtotal') }}</span>
                                <span class="font-semibold text-gray-800">${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>{{ __('Tax') }}</span>
                                <span class="font-semibold text-gray-800">${{ number_format($order->tax, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between text-gray-600">
                                <span>{{ __('Shipping') }}</span>
                                <span class="font-semibold text-green-600 flex items-center gap-1">
                                    <i class="fas fa-check-circle text-xs"></i>
                                    {{ __('Free') }}
                                </span>
                            </div>
                            
                            <div class="border-t-2 border-dashed border-gray-300 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-800">{{ __('Total') }}</span>
                                    <span class="text-2xl font-bold bg-gradient-to-r from-emerald-600 to-green-600 bg-clip-text text-transparent">
                                        ${{ number_format($order->total, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mt-8 p-6 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 rounded-2xl border border-emerald-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                            @if($order->payment_method === 'stripe')
                            <i class="fab fa-stripe-s text-white text-2xl"></i>
                            @elseif($order->payment_method === 'cash_on_delivery')
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                            @else
                            <i class="fas fa-credit-card text-white text-xl"></i>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">{{ __('Payment Method') }}</p>
                            <p class="font-bold text-gray-800 text-xl capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                        </div>
                        <div class="ml-auto">
                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                {{ __('Confirmed') }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($order->notes)
                <!-- Order Notes -->
                <div class="mt-8 p-6 bg-amber-50 rounded-2xl border-l-4 border-amber-400">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-sticky-note text-amber-600"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-2">{{ __('Order Notes') }}</h4>
                            <p class="text-gray-700">{{ $order->notes }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Progress Timeline -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden p-8 md:p-10 mb-10 border border-white/20 animate-fade-in-up animation-delay-400">
            <div class="text-center mb-10">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">{{ __('What happens next?') }}</h3>
                <p class="text-gray-500">{{ __('Track your order progress') }}</p>
            </div>
            
            <div class="relative max-w-3xl mx-auto">
                <!-- Timeline line -->
                <div class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-emerald-500 via-blue-500 to-purple-500 rounded-full"></div>
                
                <div class="space-y-12">
                    <!-- Step 1 -->
                    <div class="relative flex items-center md:justify-center">
                        <div class="flex items-center gap-6 md:w-1/2 md:pr-12 md:justify-end">
                            <div class="hidden md:block text-right">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Order Confirmed') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('Your order has been received') }}</p>
                            </div>
                        </div>
                        <div class="absolute left-0 md:left-1/2 md:-translate-x-1/2 w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white z-10">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12">
                            <div class="md:hidden">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Order Confirmed') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('Your order has been received') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative flex items-center md:justify-center">
                        <div class="flex items-center gap-6 md:w-1/2 md:pr-12 md:justify-end">
                            <div class="hidden md:block text-right">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Processing') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('We are preparing your order') }}</p>
                            </div>
                        </div>
                        <div class="absolute left-0 md:left-1/2 md:-translate-x-1/2 w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white z-10">
                            <i class="fas fa-box-open text-white"></i>
                        </div>
                        <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12">
                            <div class="md:hidden">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Processing') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('We are preparing your order') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="relative flex items-center md:justify-center">
                        <div class="flex items-center gap-6 md:w-1/2 md:pr-12 md:justify-end">
                            <div class="hidden md:block text-right">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Shipped') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('On the way to you') }}</p>
                            </div>
                        </div>
                        <div class="absolute left-0 md:left-1/2 md:-translate-x-1/2 w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white z-10">
                            <i class="fas fa-shipping-fast text-white"></i>
                        </div>
                        <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12">
                            <div class="md:hidden">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Shipped') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('On the way to you') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="relative flex items-center md:justify-center">
                        <div class="flex items-center gap-6 md:w-1/2 md:pr-12 md:justify-end">
                            <div class="hidden md:block text-right">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Delivered') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('Enjoy your purchase!') }}</p>
                            </div>
                        </div>
                        <div class="absolute left-0 md:left-1/2 md:-translate-x-1/2 w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center shadow-lg ring-4 ring-white z-10">
                            <i class="fas fa-home text-gray-400"></i>
                        </div>
                        <div class="ml-20 md:ml-0 md:w-1/2 md:pl-12">
                            <div class="md:hidden">
                                <h4 class="font-bold text-gray-800 text-lg">{{ __('Delivered') }}</h4>
                                <p class="text-gray-500 text-sm">{{ __('Enjoy your purchase!') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10 animate-fade-in-up animation-delay-600">
            <a href="{{ route('home') }}" 
               class="group inline-flex items-center justify-center px-8 py-4 bg-white border-2 border-gray-200 text-gray-700 rounded-2xl font-bold hover:border-emerald-500 hover:text-emerald-600 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform"></i>
                {{ __('Continue Shopping') }}
            </a>
            
            @auth
            <a href="{{ route('orders.index') }}" 
               class="group inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-2xl font-bold hover:from-emerald-600 hover:to-green-700 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                <i class="fas fa-list-ul mr-3"></i>
                {{ __('View All Orders') }}
                <i class="fas fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
            </a>
            @endauth
        </div>

        <!-- Help Section -->
        <div class="text-center bg-white/80 backdrop-blur-lg rounded-3xl shadow-xl p-8 border border-white/20 animate-fade-in-up animation-delay-800">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-headset text-white text-3xl"></i>
            </div>
            <h4 class="text-xl font-bold text-gray-800 mb-2">{{ __('Need Help?') }}</h4>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">{{ __('Our customer support team is available 24/7 to assist you with any questions.') }}</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    {{ __('Email Support') }}
                </a>
                <a href="#" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-indigo-700 transition-all shadow-lg">
                    <i class="fas fa-comments mr-2"></i>
                    {{ __('Live Chat') }}
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in-up {
    from { 
        opacity: 0; 
        transform: translateY(30px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}

@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
    opacity: 0;
}

.animate-blob {
    animation: blob 7s infinite;
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}

.animation-delay-200 { animation-delay: 0.2s; }
.animation-delay-300 { animation-delay: 0.3s; }
.animation-delay-400 { animation-delay: 0.4s; }
.animation-delay-600 { animation-delay: 0.6s; }
.animation-delay-800 { animation-delay: 0.8s; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }
</style>
@endsection
