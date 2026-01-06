@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Categories') }}</h2>
        <p class="text-muted mb-0">{{ __('Manage product categories') }}</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>{{ __('Add Category') }}
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">#</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Name') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Description') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Products') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Status') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="px-4 py-3">{{ $category->id }}</td>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name['en'] ?? $category->name }}" class="rounded me-3" style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <span class="fw-semibold">{{ $category->name[app()->getLocale()] ?? $category->name['en'] }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-muted small">
                            {{ Str::limit($category->description[app()->getLocale()] ?? $category->description['en'] ?? '', 50) }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge bg-secondary">{{ $category->products_count ?? 0 }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $category->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
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
                        <td colspan="6" class="px-4 py-5 text-center text-muted">
                            <i class="fas fa-folder-open fs-3 d-block mb-2 opacity-50"></i>
                            {{ __('No categories found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
