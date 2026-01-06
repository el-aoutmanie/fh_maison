@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Contact Messages') }}</h2>
        <p class="text-muted mb-0">{{ __('View customer inquiries and messages') }}</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-envelope text-primary fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-1 small">{{ __('Total Messages') }}</p>
                        <h4 class="mb-0 fw-bold">{{ $contacts->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-calendar-day text-success fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-1 small">{{ __('Today') }}</p>
                        <h4 class="mb-0 fw-bold">{{ \App\Models\Contact::whereDate('created_at', today())->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-info bg-opacity-25 rounded-3 p-3">
                        <i class="fas fa-calendar-week text-info fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-1 small">{{ __('This Week') }}</p>
                        <h4 class="mb-0 fw-bold">{{ \App\Models\Contact::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Contacts Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3">{{ __('Name') }}</th>
                        <th class="border-0 px-4 py-3">{{ __('Email') }}</th>
                        <th class="border-0 px-4 py-3">{{ __('Subject') }}</th>
                        <th class="border-0 px-4 py-3">{{ __('Date') }}</th>
                        <th class="border-0 px-4 py-3 text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <span class="text-primary fw-bold">{{ strtoupper(substr($contact->first_name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $contact->full_name }}</div>
                                    @if($contact->phone)
                                    <small class="text-muted">{{ $contact->phone }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none">{{ $contact->email }}</a>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-truncate d-inline-block" style="max-width: 200px;">{{ $contact->subject }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <small class="text-muted">{{ $contact->created_at->format('M d, Y H:i') }}</small>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary me-1" title="{{ __('View') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this message?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('Delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                <p class="mb-0">{{ __('No contact messages yet') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($contacts->hasPages())
    <div class="card-footer bg-white border-0 py-3">
        {{ $contacts->links() }}
    </div>
    @endif
</div>
@endsection
