@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Categories') }}
    </a>
    <h2 class="fw-bold text-charcoal">{{ __('Edit Category') }}</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Name EN -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Name (English)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror" value="{{ old('name.en', $category->name['en'] ?? '') }}" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Name AR -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Name (Arabic)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror" value="{{ old('name.ar', $category->name['ar'] ?? '') }}" required dir="rtl">
                    @error('name.ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description EN -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Description (English)') }}</label>
                    <textarea name="description[en]" rows="4" class="form-control @error('description.en') is-invalid @enderror">{{ old('description.en', $category->description['en'] ?? '') }}</textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description AR -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Description (Arabic)') }}</label>
                    <textarea name="description[ar]" rows="4" class="form-control @error('description.ar') is-invalid @enderror" dir="rtl">{{ old('description.ar', $category->description['ar'] ?? '') }}</textarea>
                    @error('description.ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($category->image_url)
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Current Image') }}</label>
                    <div>
                        <img src="{{ $category->image_url }}" alt="{{ $category->name['en'] ?? $category->name }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
                @endif

                <!-- Image -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Category Image') }}</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">{{ __('Leave empty to keep current image. Recommended size: 800x600px') }}</small>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Status') }}</label>
                    <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active', $category->is_active) == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ old('is_active', $category->is_active) == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                    @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div class="mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" {{ old('show_in_menu', $category->show_in_menu) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="show_in_menu">{{ __('Show in Menu') }}</label>
                        </div>
                    </div>
                </div>

                <!-- Slug -->
                <div class="col-12">
                    <label class="form-label fw-semibold">{{ __('Slug') }}</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug ?? '') }}">
                    @error('slug')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">{{ __('Leave empty to auto-generate from English name') }}</small>
                </div>

                <!-- Actions -->
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('Update Category') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
