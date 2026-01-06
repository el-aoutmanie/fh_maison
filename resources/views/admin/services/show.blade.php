@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Services') }}
            </a>
            <h2 class="fw-bold text-charcoal mb-1">{{ __('Service Details') }}</h2>
            <p class="text-muted mb-0">{{ __('View and manage service information') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>{{ __('Edit Service') }}
            </a>
            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('{{ __('Are you sure you want to delete this service?') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                </button>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row g-4">
    <!-- Service Images -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Service Images') }}</h5>
                <span class="badge bg-secondary">{{ $service->images->count() }} {{ __('images') }}</span>
            </div>
            <div class="card-body">
                @if($service->images && $service->images->count() > 0)
                    <div x-data="{ activeImage: 0 }" class="text-center">
                        <!-- Main Image -->
                        <div class="bg-light rounded-3 overflow-hidden mb-3" style="height: 300px;">
                            @foreach($service->images as $index => $image)
                                <img x-show="activeImage === {{ $index }}"
                                     src="{{ $image->url }}"
                                     alt="{{ is_array($service->title) ? ($service->title['en'] ?? 'Service') : $service->title }}"
                                     class="w-100 h-100 object-fit-cover"
                                     style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            @endforeach
                        </div>

                        <!-- Thumbnails -->
                        @if($service->images->count() > 1)
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            @foreach($service->images as $index => $image)
                            <button @click="activeImage = {{ $index }}"
                                    :class="{'border-primary border-2': activeImage === {{ $index }}, 'border-light': activeImage !== {{ $index }}}"
                                    class="btn p-1 border rounded" type="button">
                                <img src="{{ $image->url }}"
                                     alt="{{ is_array($service->title) ? ($service->title['en'] ?? 'Service') : $service->title }}"
                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                @else
                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="height: 300px;">
                        <div class="text-center">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p>{{ __('No images uploaded') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Service Stats -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Service Stats') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3 text-center">
                            <i class="fas fa-calendar-check text-primary fs-3 mb-2"></i>
                            <h4 class="mb-0 fw-bold">{{ $service->bookedMeetings->count() }}</h4>
                            <small class="text-muted">{{ __('Total Bookings') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3 text-center">
                            <i class="fas fa-clock text-warning fs-3 mb-2"></i>
                            <h4 class="mb-0 fw-bold">{{ $service->bookedMeetings->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">{{ __('Pending') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3 text-center">
                            <i class="fas fa-check text-info fs-3 mb-2"></i>
                            <h4 class="mb-0 fw-bold">{{ $service->bookedMeetings->where('status', 'confirmed')->count() }}</h4>
                            <small class="text-muted">{{ __('Confirmed') }}</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3 text-center">
                            <i class="fas fa-check-double text-success fs-3 mb-2"></i>
                            <h4 class="mb-0 fw-bold">{{ $service->bookedMeetings->where('status', 'completed')->count() }}</h4>
                            <small class="text-muted">{{ __('Completed') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Information -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Service Information') }}</h5>
            </div>
            <div class="card-body">
                <!-- Status Badge -->
                <div class="mb-4">
                    @if($service->is_active)
                        <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i> {{ __('Active') }}</span>
                    @else
                        <span class="badge bg-secondary fs-6"><i class="fas fa-times-circle me-1"></i> {{ __('Inactive') }}</span>
                    @endif
                </div>

                <!-- Title EN -->
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">{{ __('Service Title (English)') }}</label>
                    <p class="mb-0 fs-5 fw-semibold">{{ is_array($service->title) ? ($service->title['en'] ?? '-') : $service->title }}</p>
                </div>

                <!-- Title AR -->
                @if(is_array($service->title) && isset($service->title['ar']))
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">{{ __('Service Title (Arabic)') }}</label>
                    <p class="mb-0 fs-5 fw-semibold" dir="rtl">{{ $service->title['ar'] }}</p>
                </div>
                @endif

                <hr>

                <div class="row g-3 mb-3">
                    <!-- Price -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Price') }}</label>
                        <p class="mb-0 fs-4 fw-bold text-primary">${{ number_format($service->price, 2) }}</p>
                    </div>
                    <!-- Duration -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Duration') }}</label>
                        <p class="mb-0 fs-5">
                            <i class="fas fa-clock text-muted me-2"></i>
                            {{ $service->duration }} {{ __('minutes') }}
                        </p>
                    </div>
                </div>

                <hr>

                <!-- Description EN -->
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">{{ __('Description (English)') }}</label>
                    <p class="mb-0">{{ is_array($service->description) ? ($service->description['en'] ?? '-') : ($service->description ?? '-') }}</p>
                </div>

                <!-- Description AR -->
                @if(is_array($service->description) && isset($service->description['ar']))
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">{{ __('Description (Arabic)') }}</label>
                    <p class="mb-0" dir="rtl">{{ $service->description['ar'] }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Features -->
        @if($service->features && (
            (is_array($service->features) && (
                (isset($service->features['en']) && count($service->features['en']) > 0) ||
                (isset($service->features['ar']) && count($service->features['ar']) > 0)
            ))
        ))
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Features') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Features EN -->
                    @if(isset($service->features['en']) && count($service->features['en']) > 0)
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted mb-3">{{ __('English') }}</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach($service->features['en'] as $feature)
                                @if(trim($feature))
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    {{ $feature }}
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Features AR -->
                    @if(isset($service->features['ar']) && count($service->features['ar']) > 0)
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted mb-3">{{ __('Arabic') }}</h6>
                        <ul class="list-unstyled mb-0" dir="rtl">
                            @foreach($service->features['ar'] as $feature)
                                @if(trim($feature))
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    {{ $feature }}
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Metadata -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Metadata') }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Created At') }}</label>
                        <p class="mb-0">
                            <i class="fas fa-calendar-plus text-muted me-2"></i>
                            {{ $service->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Last Updated') }}</label>
                        <p class="mb-0">
                            <i class="fas fa-calendar-check text-muted me-2"></i>
                            {{ $service->updated_at->format('M d, Y H:i') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Slug (English)') }}</label>
                        <p class="mb-0">
                            <code>{{ is_array($service->slug) ? ($service->slug['en'] ?? '-') : ($service->slug ?? '-') }}</code>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Slug (Arabic)') }}</label>
                        <p class="mb-0">
                            <code>{{ is_array($service->slug) ? ($service->slug['ar'] ?? '-') : '-' }}</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
@if($service->bookedMeetings->count() > 0)
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Recent Bookings') }}</h5>
        <a href="{{ route('admin.meetings.index') }}" class="btn btn-sm btn-outline-primary">
            {{ __('View All') }} <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">{{ __('Customer') }}</th>
                        <th class="px-4 py-3">{{ __('Date & Time') }}</th>
                        <th class="px-4 py-3">{{ __('Contact') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($service->bookedMeetings->sortByDesc('created_at')->take(5) as $meeting)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="fw-semibold">{{ $meeting->full_name }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div>
                                <i class="fas fa-calendar me-1 text-muted"></i>
                                {{ $meeting->date->format('M d, Y') }}
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                {{ $meeting->time }}
                            </small>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
