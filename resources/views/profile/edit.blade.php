@extends('layouts.frontend')

@section('title', __('Profile') . ' - ' . config('app.name'))

@section('content')
<section class="py-5 bg-light">
    <div class="container py-4">
        <h2 class="h3 fw-bold text-charcoal mb-4">{{ __('Profile') }}</h2>
        
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
