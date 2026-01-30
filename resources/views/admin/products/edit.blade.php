@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Products') }}
    </a>
    <h2 class="fw-bold text-charcoal">{{ __('Edit Product') }}</h2>
</div>

@php
    $variantData = $product->variants->map(function($variant) {
        return [
            'id' => $variant->id,
            'name' => $variant->name ?? ['en' => '', 'ar' => ''],
            'sku' => $variant->sku,
            'price' => $variant->price,
            'compare_at_price' => $variant->compare_at_price,
            'quantity' => $variant->quantity,
            'is_active' => $variant->is_active,
            'existing_images' => $variant->images->map(function($image) {
                return [
                    'id' => $image->id,
                    'url' => $image->url,
                    'name' => $image->name
                ];
            })->toArray(),
            'new_images' => []
        ];
    })->toArray();
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            <div x-data="productForm" x-init="initVariants()">
                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('productForm', () => ({
                            variants: @json($variantData),

                            initVariants() {
                                console.log('Variants initialized:', this.variants);
                            },

                            addVariant() {
                                console.log('addVariant called');
                                this.variants.push({
                                    id: null,
                                    name: { en: '', ar: '' },
                                    sku: '',
                                    price: '',
                                    compare_at_price: '',
                                    quantity: 0,
                                    is_active: 1,
                                    existing_images: [],
                                    new_images: []
                                });
                                console.log('variants count:', this.variants.length);
                            },

                            removeVariant(index) {
                                if (this.variants.length > 1) {
                                    this.variants.splice(index, 1);
                                }
                            },

                            deleteExistingImage(variantIndex, imageIndex) {
                                if (confirm('{{ addslashes(__('Are you sure you want to delete this image?')) }}')) {
                                    const imageId = this.variants[variantIndex].existing_images[imageIndex].id;
                                    // Remove from array
                                    this.variants[variantIndex].existing_images.splice(imageIndex, 1);

                                    // Send delete request
                                    fetch(`/admin/images/${imageId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        }
                                    }).then(response => {
                                        if (response.ok) {
                                            console.log('Image deleted successfully');
                                        } else {
                                            console.error('Failed to delete image');
                                        }
                                    }).catch(error => {
                                        console.error('Error deleting image:', error);
                                    });
                                }
                            },

                            previewNewImages(variantIndex, event) {
                                const files = event.target.files;
                                const variant = this.variants[variantIndex];

                                // Clear previous previews
                                variant.new_images = [];

                                for (let i = 0; i < files.length; i++) {
                                    const file = files[i];
                                    const reader = new FileReader();

                                    reader.onload = (e) => {
                                        variant.new_images.push({
                                            file: file,
                                            url: e.target.result,
                                            name: file.name
                                        });
                                    };

                                    reader.readAsDataURL(file);
                                }
                            },

                            removeNewImage(variantIndex, imageIndex) {
                                this.variants[variantIndex].new_images.splice(imageIndex, 1);
                            }
                        }));
                    });
                </script>

                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information -->
                    <div class="col-12">
                        <h5 class="fw-semibold text-primary mb-3">{{ __('Basic Information') }}</h5>
                    </div>

                    <!-- Name EN -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('Name (English)') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                            value="{{ old('name.en', $product->name['en'] ?? '') }}" required>
                        @error('name.en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name AR -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('Name (Arabic)') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror"
                            value="{{ old('name.ar', $product->name['ar'] ?? '') }}" required dir="rtl">
                        @error('name.ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description EN -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('Description (English)') }}</label>
                        <textarea name="description[en]" rows="4" class="form-control @error('description.en') is-invalid @enderror">{{ old('description.en', $product->description['en'] ?? '') }}</textarea>
                        @error('description.en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description AR -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('Description (Arabic)') }}</label>
                        <textarea name="description[ar]" rows="4" class="form-control @error('description.ar') is-invalid @enderror"
                            dir="rtl">{{ old('description.ar', $product->description['ar'] ?? '') }}</textarea>
                        @error('description.ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Measuring Unit -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('Measuring Unit') }}</label>
                        <input type="text" name="measuring_unit"
                            class="form-control @error('measuring_unit') is-invalid @enderror"
                            value="{{ old('measuring_unit', $product->measuring_unit ?? 'piece') }}"
                            placeholder="piece, kg, liter, etc.">
                        @error('measuring_unit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('Category') }} <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach ($categories ?? [] as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name[app()->getLocale()] ?? $category->name['en'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('Status') }}</label>
                        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', $product->is_active) == '1' ? 'selected' : '' }}>
                                {{ __('Active') }}</option>
                            <option value="0" {{ old('is_active', $product->is_active) == '0' ? 'selected' : '' }}>
                                {{ __('Inactive') }}</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Featured -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('Featured') }}</label>
                        <select name="is_featured" class="form-select @error('is_featured') is-invalid @enderror">
                            <option value="0" {{ old('is_featured', $product->is_featured ?? 0) == '0' ? 'selected' : '' }}>
                                {{ __('No') }}</option>
                            <option value="1" {{ old('is_featured', $product->is_featured ?? 0) == '1' ? 'selected' : '' }}>
                                {{ __('Yes') }}</option>
                        </select>
                        @error('is_featured')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Shipping Price -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('Shipping Price') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" name="shipping_price" class="form-control @error('shipping_price') is-invalid @enderror" value="{{ old('shipping_price', $product->shipping_price ?? '0') }}" placeholder="0.00">
                        </div>
                        <small class="text-muted">{{ __('Set to 0 for free shipping') }}</small>
                        @error('shipping_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Variants Section -->
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold text-primary mb-0">{{ __('Product Variants') }}</h5>
                            <button type="button" class="btn btn-outline-primary" @click="addVariant()">
                                <i class="fas fa-plus me-2"></i>{{ __('Add Variant') }}
                            </button>
                        </div>

                        <div x-show="variants.length === 0" class="alert alert-warning">
                            {{ __('No variants found. Click "Add Variant" to create your first variant.') }}
                        </div>

                        <template x-for="(variant, index) in variants" :key="index">
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ __('Variant') }} <span x-text="index + 1"></span></h6>
                                    <button type="button" class="btn btn-outline-danger btn-sm" @click="removeVariant(index)"
                                        :disabled="variants.length <= 1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" :name="'variants[' + index + '][id]'" x-model="variant.id">

                                    <!-- Variant Name EN -->
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Variant Name (English)') }}</label>
                                            <input type="text" :name="'variants[' + index + '][name][en]'"
                                                class="form-control" x-model="variant.name.en" required>
                                        </div>

                                        <!-- Variant Name AR -->
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('Variant Name (Arabic)') }}</label>
                                            <input type="text" :name="'variants[' + index + '][name][ar]'"
                                                class="form-control" x-model="variant.name.ar" dir="rtl">
                                        </div>

                                        <!-- SKU -->
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('SKU') }}</label>
                                            <input type="text" :name="'variants[' + index + '][sku]'"
                                                class="form-control" x-model="variant.sku" required>
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('Price') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" :name="'variants[' + index + '][price]'"
                                                    class="form-control" x-model="variant.price" step="0.01" required>
                                            </div>
                                        </div>

                                        <!-- Compare at Price -->
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('Compare at Price') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" :name="'variants[' + index + '][compare_at_price]'"
                                                    class="form-control" x-model="variant.compare_at_price" step="0.01">
                                            </div>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('Stock Quantity') }}</label>
                                            <input type="number" :name="'variants[' + index + '][quantity]'"
                                                class="form-control" x-model="variant.quantity" min="0" required>
                                        </div>

                                        <!-- Is Active -->
                                        <div class="col-md-3">
                                            <label class="form-label">{{ __('Active') }}</label>
                                            <select :name="'variants[' + index + '][is_active]'" class="form-select" x-model="variant.is_active">
                                                <option value="1">{{ __('Yes') }}</option>
                                                <option value="0">{{ __('No') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Existing Images -->
                                    <div class="mt-3" x-show="variant.existing_images.length > 0">
                                        <h6>{{ __('Existing Images') }}</h6>
                                        <div class="row g-2">
                                            <template x-for="(image, imageIndex) in variant.existing_images" :key="imageIndex">
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <img :src="image.url" class="card-img-top" style="height: 100px; object-fit: cover;">
                                                        <div class="card-body p-2">
                                                            <button type="button" class="btn btn-danger btn-sm w-100"
                                                                @click="deleteExistingImage(index, imageIndex)">
                                                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- New Images -->
                                    <div class="mt-3">
                                        <label class="form-label">{{ __('Add New Images') }}</label>
                                        <input type="file" :name="'variants[' + index + '][images][]'" multiple
                                            class="form-control" accept="image/*" @change="previewNewImages(index, $event)">

                                        <div class="row g-2 mt-2" x-show="variant.new_images.length > 0">
                                            <template x-for="(image, imageIndex) in variant.new_images" :key="imageIndex">
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <img :src="image.url" class="card-img-top" style="height: 100px; object-fit: cover;">
                                                        <div class="card-body p-2">
                                                            <small x-text="image.name" class="d-block text-truncate"></small>
                                                            <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-1"
                                                                @click="removeNewImage(index, imageIndex)">
                                                                <i class="fas fa-times"></i> {{ __('Remove') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Actions -->
                    <div class="col-12 mt-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('Update Product') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
