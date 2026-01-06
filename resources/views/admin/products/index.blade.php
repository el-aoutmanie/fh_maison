@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-charcoal mb-1">{{ __('Products') }}</h2>
        <p class="text-muted mb-0">{{ __('Manage your product catalog') }}</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>{{ __('Add Product') }}
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search products...') }}" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">{{ __('All Categories') }}</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name[app()->getLocale()] ?? $category->name['en'] }}
                    </option>
                    @endforeach
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
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Product') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Category') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Price') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Stock') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold">{{ __('Status') }}</th>
                        <th class="px-4 py-3 small text-uppercase fw-semibold text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="px-4 py-3">{{ $product->id }}</td>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                @if($product->images && count($product->images) > 0)
                                <img src="{{ $product->images->first()->url }}" alt="{{ $product->name['en'] ?? $product->name }}" class="rounded me-3" style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $product->name[app()->getLocale()] ?? $product->name['en'] }}</div>
                                    <small class="text-muted">{{ $product->variants->count() }} {{ __('variants') }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge bg-secondary">{{ $product->category->name[app()->getLocale()] ?? $product->category->name['en'] }}</span>
                        </td>
                        <td class="px-4 py-3 fw-semibold">
                            @if($product->variants->count() > 0)
                                @php
                                    $minPrice = $product->variants->min('price');
                                    $maxPrice = $product->variants->max('price');
                                @endphp
                                @if($minPrice == $maxPrice)
                                    ${{ number_format($minPrice, 2) }}
                                @else
                                    ${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($product->variants->count() > 0)
                                @php $totalStock = $product->variants->sum('quantity'); @endphp
                                @if($totalStock > 10)
                                <span class="badge bg-success">{{ $totalStock }}</span>
                                @elseif($totalStock > 0)
                                <span class="badge bg-warning">{{ $totalStock }}</span>
                                @else
                                <span class="badge bg-danger">{{ __('Out of Stock') }}</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">{{ __('No variants') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
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
                            <i class="fas fa-box-open fs-3 d-block mb-2 opacity-50"></i>
                            {{ __('No products found') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
