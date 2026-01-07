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
        <!-- Update Order Form -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold">{{ __('Update Order Details') }}</h5>
            </div>
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

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Order Notes') }}</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="{{ __('Add any internal notes about this order...') }}">{{ old('notes', $order->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('These notes are for internal use only') }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Payment Status') }}</label>
                        <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror">
                            <option value="pending" {{ ($order->payment_status ?? 'pending') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="paid" {{ ($order->payment_status ?? '') === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                            <option value="failed" {{ ($order->payment_status ?? '') === 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            <option value="refunded" {{ ($order->payment_status ?? '') === 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('Tracking Number') }}</label>
                        <input type="text" name="tracking_number" class="form-control @error('tracking_number') is-invalid @enderror" 
                               value="{{ old('tracking_number', $order->tracking_number ?? '') }}" 
                               placeholder="{{ __('Enter shipping tracking number') }}">
                        @error('tracking_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Add tracking number when order is shipped') }}</div>
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

        <!-- Delete Order -->
        <div class="card border-danger shadow-sm">
            <div class="card-header bg-danger bg-opacity-10 border-danger">
                <h5 class="mb-0 fw-semibold text-danger">{{ __('Danger Zone') }}</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    {{ __('Once you delete this order, all of its data will be permanently deleted. This action cannot be undone.') }}
                </p>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteOrderModal">
                    <i class="fas fa-trash me-2"></i>{{ __('Delete Order') }}
                </button>
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
                    <span class="fw-bold text-primary">${{ number_format($order->total, 2) }}</span>
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
                <p class="mb-2"><strong>{{ __('Name') }}:</strong> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                <p class="mb-2"><strong>{{ __('Email') }}:</strong> {{ $order->customer->email }}</p>
                <p class="mb-2"><strong>{{ __('Phone') }}:</strong> {{ $order->customer->phone_number }}</p>
                <p class="mb-0"><strong>{{ __('Address') }}:</strong><br>
                    {{ $order->customer->address }}<br>
                    {{ $order->customer->city }}, {{ $order->customer->state }} {{ $order->customer->postal_code }}<br>
                    {{ $order->customer->country }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteOrderModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Delete Order') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">{{ __('Are you sure you want to delete this order?') }}</p>
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>{{ __('Warning:') }}</strong> {{ __('This action cannot be undone. All order data including items and customer information will be permanently deleted.') }}
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>{{ __('Delete Order') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
