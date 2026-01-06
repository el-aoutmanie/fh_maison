@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Products') }}
            </a>
            <h2 class="fw-bold text-charcoal mb-1">{{ __('Product Details') }}</h2>
            <p class="text-muted mb-0">{{ __('View and manage product information') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>{{ __('Edit Product') }}
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('{{ __('Are you sure you want to delete this product?') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Product Images -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Product Images') }}</h5>
            </div>
            <div class="card-body">
                @if($product->images && $product->images->count() > 0)
                    <div x-data="{ activeImage: 0 }" class="text-center">
                        <!-- Main Image -->
                        <div class="bg-light rounded-3 overflow-hidden mb-3" style="height: 300px;">
                            @foreach($product->images as $index => $image)
                                <img x-show="activeImage === {{ $index }}"
                                     src="{{ $image->url }}"
                                     alt="{{ $product->name[app()->getLocale()] ?? $product->name['en'] }}"
                                     class="w-100 h-100 object-fit-cover {{ $index === 0 ? '' : 'd-none' }}">
                            @endforeach
                        </div>

                        <!-- Thumbnails -->
                        @if($product->images->count() > 1)
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            @foreach($product->images as $index => $image)
                            <button @click="activeImage = {{ $index }}"
                                    :class="{'border-primary border-2': activeImage === {{ $index }}, 'border-light': activeImage !== {{ $index }}}"
                                    class="btn p-1 border rounded" type="button">
                                <img src="{{ $image->url }}"
                                     alt="{{ $product->name[app()->getLocale()] ?? $product->name['en'] }}"
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
    </div>

    <!-- Product Information -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Product Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">{{ __('Product Name (English)') }}</label>
                    <p class="mb-0 fs-5 fw-semibold">{{ $product->name['en'] ?? '-' }}</p>
                </div>

                @if(isset($product->name['ar']))
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">{{ __('Product Name (Arabic)') }}</label>
                    <p class="mb-0 fs-5 fw-semibold" dir="rtl">{{ $product->name['ar'] }}</p>
                </div>
                @endif

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Measuring Unit') }}</label>
                        <p class="mb-0">{{ $product->measuring_unit ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Category') }}</label>
                        <p class="mb-0">
                            @if($product->category)
                                {{ $product->category->name[app()->getLocale()] ?? $product->category->name['en'] }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">{{ __('Status') }}</label>
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $product->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>
                </div>

                @if($product->variants->count() > 0)
                    @php
                        $minPrice = $product->variants->min('price');
                        $maxPrice = $product->variants->max('price');
                        $totalStock = $product->variants->sum('quantity');
                    @endphp
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">{{ __('Price Range') }}</label>
                        <p class="mb-0 fs-4 fw-bold text-primary">
                            @if($minPrice == $maxPrice)
                                ${{ number_format($minPrice, 2) }}
                            @else
                                ${{ number_format($minPrice, 2) }} - ${{ number_format($maxPrice, 2) }}
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small">{{ __('Total Stock') }}</label>
                        <p class="mb-0">
                            <span class="badge {{ $totalStock > 10 ? 'bg-success' : ($totalStock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $totalStock }} {{ $product->measuring_unit ?? 'pieces' }}
                            </span>
                        </p>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">{{ __('Featured Product') }}</label>
                    <span class="badge {{ $product->is_featured ? 'bg-info' : 'bg-light text-muted' }}">
                        {{ $product->is_featured ? __('Yes') : __('No') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Description and Features -->
<div class="row g-4 mt-2">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Description') }}</h5>
            </div>
            <div class="card-body">
                @if(isset($product->description['en']) && !empty($product->description['en']))
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary mb-2">{{ __('English') }}</h6>
                        <div class="text-muted">
                            {!! nl2br(e($product->description['en'])) !!}
                        </div>
                    </div>
                @endif

                @if(isset($product->description['ar']) && !empty($product->description['ar']))
                    <div class="mb-4">
                        <h6 class="fw-semibold text-primary mb-2">{{ __('Arabic') }}</h6>
                        <div class="text-muted" dir="rtl">
                            {!! nl2br(e($product->description['ar'])) !!}
                        </div>
                    </div>
                @endif

                @if((!isset($product->description['en']) || empty($product->description['en'])) && (!isset($product->description['ar']) || empty($product->description['ar'])))
                    <p class="text-muted mb-0">{{ __('No description available') }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Key Features') }}</h5>
            </div>
            <div class="card-body">
                @if($product->features && count($product->features) > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($product->features as $feature)
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="fas fa-check-circle text-success mt-1 flex-shrink-0"></i>
                            <span class="text-muted">
                                @if(is_array($feature))
                                    <strong>{{ __('English') }}:</strong> {{ $feature['en'] ?? '-' }}<br>
                                    <strong>{{ __('Arabic') }}:</strong> {{ $feature['ar'] ?? '-' }}
                                @else
                                    {{ $feature }}
                                @endif
                            </span>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">{{ __('No features specified') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Variants -->
@if($product->variants->count() > 0)
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Product Variants') }}</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Variant Name') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Compare Price') }}</th>
                        <th>{{ __('Stock') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product->variants as $variant)
                    <tr>
                        <td>
                            @if(is_array($variant->name))
                                <div class="mb-1"><strong>{{ __('EN') }}:</strong> {{ $variant->name['en'] ?? '-' }}</div>
                                <div><strong>{{ __('AR') }}:</strong> {{ $variant->name['ar'] ?? '-' }}</div>
                            @else
                                {{ $variant->name ?? '-' }}
                            @endif
                        </td>
                        <td class="fw-semibold text-primary">${{ number_format($variant->price, 2) }}</td>
                        <td>
                            @if($variant->compare_at_price)
                                ${{ number_format($variant->compare_at_price, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $variant->quantity > 10 ? 'bg-success' : ($variant->quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $variant->quantity }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $variant->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $variant->is_active ? __('Active') : __('Inactive') }}
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

<!-- Product Statistics -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-semibold text-charcoal">{{ __('Product Statistics') }}</h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2 fw-bold text-primary">{{ $product->images->count() }}</div>
                    <div class="text-muted small">{{ __('Images') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2 fw-bold text-success">{{ $product->variants->count() }}</div>
                    <div class="text-muted small">{{ __('Variants') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2 fw-bold text-info">{{ $product->variants->sum('quantity') }}</div>
                    <div class="text-muted small">{{ __('Total Stock') }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <div class="fs-2 fw-bold text-warning">{{ $product->created_at->diffForHumans() }}</div>
                    <div class="text-muted small">{{ __('Created') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
