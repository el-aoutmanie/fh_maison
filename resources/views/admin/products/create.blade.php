@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Products') }}
    </a>
    <h2 class="fw-bold text-charcoal">{{ __('Create Product') }}</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" x-data="{
            variants: [
                {
                    name: { en: '', ar: '' },
                    sku: '',
                    price: '',
                    compare_at_price: '',
                    quantity: 0,
                    is_active: 1,
                    images: []
                }
            ],

            addVariant() {
                console.log('addVariant called');
                this.variants.push({
                    name: { en: '', ar: '' },
                    sku: '',
                    price: '',
                    compare_at_price: '',
                    quantity: 0,
                    is_active: 1,
                    images: []
                });
                console.log('variants count:', this.variants.length);
            },

            removeVariant(index) {
                if (this.variants.length > 1) {
                    this.variants.splice(index, 1);
                }
            }
        }">
            @csrf

            <div class="row g-4">
                <!-- Basic Information -->
                <div class="col-12">
                    <h5 class="fw-semibold text-primary mb-3">{{ __('Basic Information') }}</h5>
                </div>

                <!-- Name EN -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Name (English)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror" value="{{ old('name.en') }}" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Name AR -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Name (Arabic)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror" value="{{ old('name.ar') }}" required dir="rtl">
                    @error('name.ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Measuring Unit -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ __('Measuring Unit') }}</label>
                    <input type="text" name="measuring_unit" class="form-control @error('measuring_unit') is-invalid @enderror" value="{{ old('measuring_unit', 'piece') }}" placeholder="piece, kg, liter, etc.">
                    @error('measuring_unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ __('Category') }} <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">{{ __('Select Category') }}</option>
                        @foreach($categories ?? [] as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name[app()->getLocale()] ?? $category->name['en'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ __('Status') }}</label>
                    <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                    @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Shipping Price -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ __('Shipping Price') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0" name="shipping_price" class="form-control @error('shipping_price') is-invalid @enderror" value="{{ old('shipping_price', '0') }}" placeholder="0.00">
                    </div>
                    <small class="text-muted">{{ __('Set to 0 for free shipping') }}</small>
                    @error('shipping_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description EN -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Description (English)') }}</label>
                    <textarea name="description[en]" rows="4" class="form-control @error('description.en') is-invalid @enderror">{{ old('description.en') }}</textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description AR -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Description (Arabic)') }}</label>
                    <textarea name="description[ar]" rows="4" class="form-control @error('description.ar') is-invalid @enderror" dir="rtl">{{ old('description.ar') }}</textarea>
                    @error('description.ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Features -->
                <div class="col-12">
                    <label class="form-label fw-semibold">{{ __('Key Features') }}</label>
                    <div x-data="{ features: [''] }" class="mb-3">
                        <template x-for="(feature, index) in features" :key="index">
                            <div class="input-group mb-2">
                                <input type="text" :name="'features[' + index + ']'" x-model="features[index]" class="form-control" placeholder="{{ __('Enter a feature') }}">
                                <button type="button" @click="features.splice(index, 1)" class="btn btn-outline-danger" x-show="features.length > 1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="features.push('')" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>{{ __('Add Feature') }}
                        </button>
                    </div>
                </div>

                <!-- Variants -->
                <div class="col-12 mt-4">
                    <h5 class="fw-semibold text-primary mb-3">{{ __('Product Variants') }}</h5>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Each product must have at least one variant. Variants allow you to have different prices, stock levels, and images for the same product.') }}
                    </div>
                </div>

                <!-- Variants Container -->
                <div class="col-12">
                    <div class="variants-container">
                        <template x-for="(variant, index) in variants" :key="index">
                            <div class="card border mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 fw-semibold">{{ __('Variant') }} <span x-text="index + 1"></span></h6>
                                    <button type="button" @click="removeVariant(index)" class="btn btn-sm btn-outline-danger" x-show="variants.length > 1">
                                        <i class="fas fa-trash me-1"></i>{{ __('Remove') }}
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Variant Name EN -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('Variant Name (English)') }} <span class="text-danger">*</span></label>
                                            <input type="text" :name="'variants[' + index + '][name][en]'" x-model="variant.name.en" class="form-control" required>
                                        </div>

                                        <!-- Variant Name AR -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('Variant Name (Arabic)') }}</label>
                                            <input type="text" :name="'variants[' + index + '][name][ar]'" x-model="variant.name.ar" class="form-control" dir="rtl">
                                        </div>

                                        <!-- SKU -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">{{ __('SKU') }} <span class="text-danger">*</span></label>
                                            <input type="text" :name="'variants[' + index + '][sku]'" x-model="variant.sku" class="form-control" required>
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">{{ __('Price') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" :name="'variants[' + index + '][price]'" x-model="variant.price" step="0.01" class="form-control" required>
                                            </div>
                                        </div>

                                        <!-- Compare at Price -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">{{ __('Compare at Price') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" :name="'variants[' + index + '][compare_at_price]'" x-model="variant.compare_at_price" step="0.01" class="form-control">
                                            </div>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('Stock Quantity') }} <span class="text-danger">*</span></label>
                                            <input type="number" :name="'variants[' + index + '][quantity]'" x-model="variant.quantity" class="form-control" required min="0">
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">{{ __('Status') }}</label>
                                            <select :name="'variants[' + index + '][is_active]'" x-model="variant.is_active" class="form-select">
                                                <option value="1">{{ __('Active') }}</option>
                                                <option value="0">{{ __('Inactive') }}</option>
                                            </select>
                                        </div>

                                        <!-- Images -->
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">{{ __('Variant Images') }}</label>
                                            <input type="file" :name="'variants[' + index + '][images][]'" multiple class="form-control" accept="image/*">
                                            <small class="text-muted">{{ __('You can select multiple images for this variant.') }}</small>

                                            <!-- Image Preview -->
                                            <div :id="'variantImagePreview' + index" class="row g-3 mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <button type="button" @click="addVariant()" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('Add Variant') }}
                        </button>
                    </div>
                </div>

                <!-- Featured -->
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_featured" class="form-check-input @error('is_featured') is-invalid @enderror" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold">{{ __('Mark as Featured Product') }}</label>
                        @error('is_featured')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-12 mt-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('Create Product') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Alpine.js data is now defined inline in the form
</script>
@endpush
