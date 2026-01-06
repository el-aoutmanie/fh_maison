@extends('layouts.admin')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Orders Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary rounded-3 p-3">
                        <i class="fas fa-shopping-bag text-white fs-4"></i>
                    </div>
                    <div class="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'me' : 'ms' }}-3">
                        <p class="text-muted mb-1 small">{{ __('Total Orders') }}</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_orders'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning rounded-3 p-3">
                        <i class="fas fa-clock text-white fs-4"></i>
                    </div>
                    <div class="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'me' : 'ms' }}-3">
                        <p class="text-muted mb-1 small">{{ __('Pending Orders') }}</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['pending_orders'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Products Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success rounded-3 p-3">
                        <i class="fas fa-box text-white fs-4"></i>
                    </div>
                    <div class="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'me' : 'ms' }}-3">
                        <p class="text-muted mb-1 small">{{ __('Total Products') }}</p>
                        <h3 class="mb-0 fw-bold">{{ $stats['total_products'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue Card -->
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 rounded-3 p-3" style="background-color: #6f42c1;">
                        <i class="fas fa-dollar-sign text-white fs-4"></i>
                    </div>
                    <div class="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'me' : 'ms' }}-3">
                        <p class="text-muted mb-1 small">{{ __('Total Revenue') }}</p>
                        <h3 class="mb-0 fw-bold">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-list-alt me-2 text-primary"></i>
            {{ __('Recent Orders') }}
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 text-{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'end' : 'start' }} small text-uppercase fw-semibold">
                            {{ __('Order Code') }}
                        </th>
                        <th class="px-4 py-3 text-{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'end' : 'start' }} small text-uppercase fw-semibold">
                            {{ __('Customer') }}
                        </th>
                        <th class="px-4 py-3 text-{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'end' : 'start' }} small text-uppercase fw-semibold">
                            {{ __('Total') }}
                        </th>
                        <th class="px-4 py-3 text-{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'end' : 'start' }} small text-uppercase fw-semibold">
                            {{ __('Status') }}
                        </th>
                        <th class="px-4 py-3 text-{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'end' : 'start' }} small text-uppercase fw-semibold">
                            {{ __('Date') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_orders'] as $order)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="fw-semibold text-dark">{{ $order->code }}</span>
                        </td>
                        <td class="px-4 py-3 text-muted">
                            {{ $order->customer->full_name }}
                        </td>
                        <td class="px-4 py-3 text-muted">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge 
                                @if($order->status === 'pending') bg-warning text-dark
                                @elseif($order->status === 'processing') bg-info
                                @elseif($order->status === 'delivered') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-muted">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-5 text-center text-muted">
                            <i class="fas fa-inbox fs-3 d-block mb-2 opacity-50"></i>
                            {{ __('No orders yet') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
