@extends('layouts.admin')

@section('title', __('Booked Meetings'))

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('Booked Meetings') }}</h1>
            <p class="text-muted mb-0">{{ __('Manage service bookings and appointments') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            @if($meetings->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No meetings booked yet') }}</h5>
                    <p class="text-muted">{{ __('Meeting bookings will appear here') }}</p>
                </div>
            @else
                <div class="table-responsive" style="overflow: visible;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 text-uppercase small fw-semibold">{{ __('ID') }}</th>
                                <th class="px-4 py-3 text-uppercase small fw-semibold">{{ __('Customer') }}</th>
                                <th class="px-4 py-3 text-uppercase small fw-semibold">{{ __('Service') }}</th>
                                <th class="px-4 py-3 text-uppercase small fw-semibold">{{ __('Date & Time') }}</th>
                                <th class="px-4 py-3 text-uppercase small fw-semibold">{{ __('Contact') }}</th>
                                <th class="px-4 py-3 text-uppercase small fw-semibold">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-uppercase small fw-semibold text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meetings as $meeting)
                            <tr>
                                <td class="px-4 py-3">
                                    <span class="badge bg-secondary">#{{ $meeting->id }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="fw-semibold">{{ $meeting->full_name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($meeting->service)
                                        <div class="fw-semibold">{{ is_array($meeting->service->title) ? ($meeting->service->title[app()->getLocale()] ?? $meeting->service->title['en'] ?? '') : ($meeting->service->title ?? '') }}</div>
                                        <small class="text-muted">${{ number_format($meeting->service->price, 2) }}</small>
                                    @else
                                        <span class="text-muted">{{ __('Service deleted') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div>
                                        <i class="fas fa-calendar me-1 text-muted"></i>
                                        <span>{{ $meeting->date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $meeting->time }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="small">
                                        <div><i class="fas fa-envelope me-1 text-muted"></i> {{ $meeting->email }}</div>
                                        <div><i class="fas fa-phone me-1 text-muted"></i> {{ $meeting->phone_number }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $color = $statusColors[$meeting->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ __(ucfirst($meeting->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            @if($meeting->status !== 'confirmed')
                                            <li>
                                                <form action="{{ route('admin.meetings.update-status', $meeting) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check text-info me-2"></i> {{ __('Confirm') }}
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if($meeting->status !== 'completed')
                                            <li>
                                                <form action="{{ route('admin.meetings.update-status', $meeting) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-check-double text-success me-2"></i> {{ __('Complete') }}
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if($meeting->status !== 'cancelled')
                                            <li>
                                                <form action="{{ route('admin.meetings.update-status', $meeting) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-times text-danger me-2"></i> {{ __('Cancel') }}
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                            @if($meeting->message)
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#messageModal{{ $meeting->id }}">
                                                    <i class="fas fa-comment text-primary me-2"></i> {{ __('View Message') }}
                                                </button>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>

                                    @if($meeting->message)
                                    <!-- Message Modal -->
                                    <div class="modal fade" id="messageModal{{ $meeting->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Customer Message') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-2"><strong>{{ __('From:') }}</strong> {{ $meeting->full_name }}</p>
                                                    <p class="mb-2"><strong>{{ __('Email:') }}</strong> {{ $meeting->email }}</p>
                                                    <hr>
                                                    <p class="mb-0">{{ $meeting->message }}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-top">
                    {{ $meetings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .table-responsive {
        overflow-x: auto;
        overflow-y: visible !important;
    }
    .dropdown-menu {
        z-index: 1050;
    }
</style>
@endpush
@endsection
