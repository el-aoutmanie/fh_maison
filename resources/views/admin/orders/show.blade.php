@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Orders') }}
    </a>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-charcoal mb-1">{{ __('Order') }} #{{ $order->code }}</h2>
            <p class="text-muted mb-0">{{ __('Placed on') }} {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-outline-primary me-2">
                <i class="fas fa-print me-2"></i>{{ __('Print Invoice') }}
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Order Status & Info -->
    <div class="col-lg-8">
        <!-- Status Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">{{ __('Order Status') }}</h5>
                    <span class="badge 
                        @if($order->status === 'pending') bg-warning text-dark
                        @elseif($order->status === 'processing') bg-info
                        @elseif($order->status === 'shipped') bg-primary
                        @elseif($order->status === 'delivered') bg-success
                        @else bg-danger
                        @endif fs-6">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PATCH')
                    <div class="col-md-8">
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sync me-2"></i>{{ __('Update Status') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-semibold mb-0">{{ __('Order Items') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">{{ __('Product') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('Quantity') }}</th>
                                <th class="px-4 py-3 text-end">{{ __('Price') }}</th>
                                <th class="px-4 py-3 text-end">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->images)
                                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product_name }}" class="rounded me-3" style="width: 56px; height: 56px; object-fit: cover;">
                                        @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $item->product_name }}</div>
                                            @if($item->variant_name)
                                            <small class="text-muted">{{ $item->variant_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                </td>
                                <td class="px-4 py-3 text-end">${{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-end fw-semibold">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-end fw-semibold">{{ __('Subtotal') }}</td>
                                <td class="px-4 py-3 text-end fw-semibold">${{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-end text-success">{{ __('Discount') }}</td>
                                <td class="px-4 py-3 text-end text-success">-${{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-end fw-semibold">{{ __('Shipping') }}</td>
                                <td class="px-4 py-3 text-end fw-semibold">${{ number_format($order->shipping_amount ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-end fw-bold fs-5">{{ __('Total') }}</td>
                                <td class="px-4 py-3 text-end fw-bold fs-5 text-primary">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer & Shipping Info -->
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0">{{ __('Customer Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $order->customer->full_name }}</div>
                        <small class="text-muted">{{ $order->customer->email }}</small>
                    </div>
                </div>
                @if($order->customer->phone)
                <div class="d-flex align-items-center text-muted small">
                    <i class="fas fa-phone me-2"></i>
                    <span>{{ $order->customer->phone }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Shipping Address -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0">{{ __('Shipping Address') }}</h6>
            </div>
            <div class="card-body">
                <p class="mb-1 fw-semibold">{{ $order->shipping_name ?? $order->customer->full_name }}</p>
                <p class="mb-1 text-muted small">{{ $order->shipping_address }}</p>
                <p class="mb-1 text-muted small">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                <p class="mb-0 text-muted small">{{ $order->shipping_country }}</p>
                @if($order->shipping_phone)
                <div class="d-flex align-items-center text-muted small mt-2">
                    <i class="fas fa-phone me-2"></i>
                    <span>{{ $order->shipping_phone }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0">{{ __('Payment Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">{{ __('Method') }}</span>
                    <span class="fw-semibold">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">{{ __('Status') }}</span>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
