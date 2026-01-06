@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Services') }}
    </a>
    <h2 class="fw-bold text-charcoal">{{ __('Edit Service') }}</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Title EN -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Title (English)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="title[en]" class="form-control @error('title.en') is-invalid @enderror" value="{{ old('title.en', is_array($service->title) ? ($service->title['en'] ?? '') : $service->title) }}" required>
                    @error('title.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Title AR -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Title (Arabic)') }} <span class="text-danger">*</span></label>
                    <input type="text" name="title[ar]" class="form-control @error('title.ar') is-invalid @enderror" value="{{ old('title.ar', is_array($service->title) ? ($service->title['ar'] ?? '') : '') }}" required dir="rtl">
                    @error('title.ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description EN -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Description (English)') }}</label>
                    <textarea name="description[en]" rows="4" class="form-control @error('description.en') is-invalid @enderror">{{ old('description.en', is_array($service->description) ? ($service->description['en'] ?? '') : $service->description) }}</textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description AR -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Description (Arabic)') }}</label>
                    <textarea name="description[ar]" rows="4" class="form-control @error('description.ar') is-invalid @enderror" dir="rtl">{{ old('description.ar', is_array($service->description) ? ($service->description['ar'] ?? '') : '') }}</textarea>
                    @error('description.ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ __('Price') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $service->price) }}" required>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Duration -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ __('Duration (minutes)') }} <span class="text-danger">*</span></label>
                    <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $service->duration) }}" required>
                    @error('duration')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">{{ __('Status') }}</label>
                    <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active', $service->is_active) == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="0" {{ old('is_active', $service->is_active) == '0' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    </select>
                    @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Current Images -->
                @if($service->images && count($service->images) > 0)
                <div class="col-12 mt-4">
                    <label class="form-label fw-semibold">{{ __('Current Images') }}</label>
                    <div class="row g-3" id="existing-images-container">
                        @foreach($service->images as $image)
                        <div class="col-auto" id="image-{{ $image->id }}">
                            <div class="card" style="width: 150px;">
                                <img src="{{ $image->url }}" alt="{{ $service->title['en'] ?? $service->title }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteImage({{ $image->id }})">
                                        <i class="fas fa-trash"></i> {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- New Images -->
                <div class="col-12 mt-4">
                    <label class="form-label fw-semibold">{{ __('Add New Images') }}</label>
                    <input type="file" name="images[]" multiple class="form-control @error('images') is-invalid @enderror" accept="image/*" id="new-images-input" onchange="previewImages(event)">
                    @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">{{ __('Upload new images to add to existing ones. Recommended size: 800x600px') }}</small>
                    
                    <!-- Preview Container -->
                    <div class="row g-3 mt-2" id="preview-container"></div>
                </div>

                <!-- Features EN -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Features (English)') }}</label>
                    <textarea name="features[en]" rows="3" class="form-control @error('features.en') is-invalid @enderror" placeholder="{{ __('One feature per line') }}">{{ old('features.en', is_array($service->features['en'] ?? null) ? implode("\n", $service->features['en']) : '') }}</textarea>
                    @error('features.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">{{ __('Enter each feature on a new line') }}</small>
                </div>

                <!-- Features AR -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">{{ __('Features (Arabic)') }}</label>
                    <textarea name="features[ar]" rows="3" class="form-control @error('features.ar') is-invalid @enderror" placeholder="{{ __('One feature per line') }}" dir="rtl">{{ old('features.ar', is_array($service->features['ar'] ?? null) ? implode("\n", $service->features['ar']) : '') }}</textarea>
                    @error('features.ar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">{{ __('Enter each feature on a new line') }}</small>
                </div>

                <!-- Actions -->
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('Update Service') }}
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
// Preview new images before upload
function previewImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = ''; // Clear previous previews
    
    if (files.length === 0) return;
    
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-auto';
            
            const card = document.createElement('div');
            card.className = 'card';
            card.style.width = '150px';
            
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'card-img-top';
            img.style.height = '150px';
            img.style.objectFit = 'cover';
            
            const cardBody = document.createElement('div');
            cardBody.className = 'card-body p-2';
            
            const fileName = document.createElement('small');
            fileName.className = 'text-muted d-block text-truncate';
            fileName.textContent = file.name;
            
            cardBody.appendChild(fileName);
            card.appendChild(img);
            card.appendChild(cardBody);
            col.appendChild(card);
            previewContainer.appendChild(col);
        };
        
        reader.readAsDataURL(file);
    });
}

// Delete existing image
function deleteImage(imageId) {
    if (!confirm('{{ __("Are you sure you want to delete this image?") }}')) {
        return;
    }
    
    fetch(`/admin/services/images/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`image-${imageId}`).remove();
            
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.querySelector('.card-body').prepend(alert);
            
            setTimeout(() => alert.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("An error occurred while deleting the image") }}');
    });
}
</script>
@endpush
