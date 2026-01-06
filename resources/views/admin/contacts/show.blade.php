@extends('layouts.admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Messages') }}
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Message Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">{{ $contact->subject }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p class="text-stone" style="white-space: pre-wrap;">{{ $contact->message }}</p>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        {{ $contact->created_at->format('F d, Y \a\t H:i') }}
                    </small>
                    <div>
                        <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i>{{ __('Reply via Email') }}
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this message?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>{{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Sender Info Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0 fw-bold">{{ __('Sender Information') }}</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <span class="text-primary fw-bold fs-4">{{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $contact->full_name }}</h6>
                        <small class="text-muted">{{ __('Contact') }}</small>
                    </div>
                </div>
                
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-center py-2 border-bottom">
                        <i class="fas fa-envelope text-primary me-3" style="width: 20px;"></i>
                        <div>
                            <small class="text-muted d-block">{{ __('Email') }}</small>
                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none">{{ $contact->email }}</a>
                        </div>
                    </li>
                    @if($contact->phone)
                    <li class="d-flex align-items-center py-2 border-bottom">
                        <i class="fas fa-phone text-primary me-3" style="width: 20px;"></i>
                        <div>
                            <small class="text-muted d-block">{{ __('Phone') }}</small>
                            <a href="tel:{{ $contact->phone }}" class="text-decoration-none">{{ $contact->phone }}</a>
                        </div>
                    </li>
                    @endif
                    @if($contact->ip)
                    <li class="d-flex align-items-center py-2">
                        <i class="fas fa-globe text-primary me-3" style="width: 20px;"></i>
                        <div>
                            <small class="text-muted d-block">{{ __('IP Address') }}</small>
                            <span>{{ $contact->ip }}</span>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
