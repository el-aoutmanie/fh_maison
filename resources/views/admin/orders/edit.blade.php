@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Orders') }}
    </a>
    <h2 class="fw-bold text-charcoal">{{ __('Edit Order') }} #{{ $order->code }}</h2>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Order Status') }}</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Update the order status to reflect its current state') }}</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('Update Order') }}
                        </button>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-secondary">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">{{ __('Order Summary') }}</h5>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">{{ __('Order Code') }}:</span>
                    <span class="fw-semibold">{{ $order->code }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">{{ __('Date') }}:</span>
                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">{{ __('Total') }}:</span>
                    <span class="fw-bold text-primary">{{ number_format($order->total_amount, 2) }} {{ __('MAD') }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">{{ __('Current Status') }}:</span>
                    <span class="badge 
                        @if($order->status === 'pending') bg-warning text-dark
                        @elseif($order->status === 'processing') bg-info
                        @elseif($order->status === 'shipped') bg-primary
                        @elseif($order->status === 'delivered') bg-success
                        @else bg-danger
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">{{ __('Customer Information') }}</h5>
                <p class="mb-2"><strong>{{ __('Name') }}:</strong> {{ $order->customer->full_name ?? $order->shipping_full_name }}</p>
                <p class="mb-2"><strong>{{ __('Email') }}:</strong> {{ $order->customer->email ?? $order->shipping_email }}</p>
                <p class="mb-2"><strong>{{ __('Phone') }}:</strong> {{ $order->customer->phone_number ?? $order->shipping_phone }}</p>
                <p class="mb-0"><strong>{{ __('Address') }}:</strong><br>{{ $order->shipping_address }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
