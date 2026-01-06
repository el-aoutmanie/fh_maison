@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Users') }}</h2>
        <p class="text-muted mb-0">{{ __('Manage users and their roles') }}</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>{{ __('Add User') }}
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search users...') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">{{ __('All Roles') }}</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                    <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>{{ __('Manager') }}</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>{{ __('Customer') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                </select>
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
                        <th class="px-4 py-3 small text-uppercase fw-semibold">#</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('User') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Email') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Phone') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Role') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Status') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Created') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3 text-muted">{{ $user->id }}</td>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #D4A574, #9C6644);">
                                    <span class="text-white fw-bold">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <span class="fw-semibold d-block">{{ $user->first_name }} {{ $user->last_name }}</span>
                                    <small class="text-muted">{{ '@' . $user->user_name }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->phone_number)
                                <a href="tel:{{ $user->phone_number }}" class="text-decoration-none">{{ $user->phone_number }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">{{ __('Admin') }}</span>
                            @elseif($user->role === 'manager')
                                <span class="badge bg-warning text-dark">{{ __('Manager') }}</span>
                            @else
                                <span class="badge bg-info">{{ __('Customer') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       class="form-check-input status-toggle" 
                                       data-user-id="{{ $user->id }}"
                                       {{ $user->is_active ? 'checked' : '' }}
                                       {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-muted small">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3 text-end">
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.users.show', $user) }}">
                                            <i class="fas fa-eye me-2 text-info"></i>{{ __('View') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}">
                                            <i class="fas fa-edit me-2 text-primary"></i>{{ __('Edit') }}
                                        </a>
                                    </li>
                                    @if($user->id !== auth()->id())
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-5 text-center text-muted">
                            <i class="fas fa-users fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">{{ __('No users found') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('.status-toggle').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const userId = this.dataset.userId;
        const checkbox = this;
        
        fetch(`{{ url('/' . LaravelLocalization::getCurrentLocale() . '/admin/users') }}/${userId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show toast notification
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 m-4 px-4 py-3 rounded-3 shadow-lg bg-success text-white';
                toast.style.zIndex = '9999';
                toast.innerHTML = `<i class="fas fa-check-circle me-2"></i>${data.message}`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            } else {
                checkbox.checked = !checkbox.checked;
                alert(data.message);
            }
        })
        .catch(error => {
            checkbox.checked = !checkbox.checked;
            console.error('Error:', error);
        });
    });
});
</script>
@endpush
@endsection
