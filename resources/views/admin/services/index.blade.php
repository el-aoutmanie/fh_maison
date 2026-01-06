@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Services') }}</h2>
        <p class="text-muted mb-0">{{ __('Manage your services') }}</p>
    </div>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>{{ __('Add Service') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">#</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Service') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Price') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Duration') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Bookings') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Status') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td class="px-4 py-3">{{ $service->id }}</td>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                @if($service->images && $service->images->count() > 0)
                                <img src="{{ $service->images->first()->url }}" alt="{{ $service->title['en'] ?? $service->title }}" class="rounded me-3" style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-briefcase text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ is_array($service->title) ? ($service->title[app()->getLocale()] ?? $service->title['en'] ?? '') : ($service->title ?? '') }}</div>
                                    <small class="text-muted">{{ Str::limit(is_array($service->description) ? ($service->description[app()->getLocale()] ?? $service->description['en'] ?? '') : ($service->description ?? ''), 40) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 fw-semibold">${{ number_format($service->price, 2) }}</td>
                        <td class="px-4 py-3">
                            <span class="badge bg-info">{{ $service->duration }} {{ __('min') }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge bg-secondary">{{ $service->booked_meetings_count ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $service->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.services.show', $service) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-5 text-center text-muted">
                            <i class="fas fa-briefcase fs-3 d-block mb-2 opacity-50"></i>
                            {{ __('No services found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($services->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $services->links() }}
    </div>
    @endif
</div>
@endsection
