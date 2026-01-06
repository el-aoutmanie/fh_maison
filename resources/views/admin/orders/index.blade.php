@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Orders') }}</h2>
        <p class="text-muted mb-0">{{ __('Manage customer orders') }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-clock text-warning fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-1 small">{{ __('Pending') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $stats['pending'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-sync text-info fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-1 small">{{ __('Processing') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $stats['processing'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-1 small">{{ __('Completed') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $stats['completed'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-danger bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-times-circle text-danger fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-1 small">{{ __('Cancelled') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $stats['cancelled'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search by order code or customer...') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('Shipped') }}</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i>{{ __('Filter') }}
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Order Code') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Customer') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Items') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Total') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Status') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Date') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-4 py-3">
                            <span class="fw-semibold text-primary">{{ $order->code }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <div class="fw-semibold">{{ $order->customer->full_name }}</div>
                                <small class="text-muted">{{ $order->customer->email }}</small>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge bg-secondary">{{ $order->items_count ?? count($order->items) }} {{ __('items') }}</span>
                        </td>
                        <td class="px-4 py-3 fw-semibold">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="badge 
                                @if($order->status === 'pending') bg-warning text-dark
                                @elseif($order->status === 'processing') bg-info
                                @elseif($order->status === 'shipped') bg-primary
                                @elseif($order->status === 'delivered') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-muted small">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-5 text-center text-muted">
                            <i class="fas fa-shopping-bag fs-3 d-block mb-2 opacity-50"></i>
                            {{ __('No orders found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
