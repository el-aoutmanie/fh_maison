@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
@endphp

<aside class="position-fixed top-0 bottom-0 bg-dark text-white d-flex flex-column admin-sidebar"
       style="width: 256px; z-index: 1040; {{ $isRtl ? 'right: 0;' : 'left: 0;' }}"
       :class="{ 'sidebar-hidden': !sidebarOpen }">
    <div class="d-flex align-items-center justify-content-between p-4 border-bottom border-secondary">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #D4A574, #9C6644);">
                <span class="text-white fw-bold fs-5">A</span>
            </div>
            <span class="fs-5 fw-semibold">{{ config('app.name') }}</span>
        </div>
        <button @click="sidebarOpen = false" class="btn btn-link text-white p-0" style="border: none;">
            <i class="fas fa-times fs-5"></i>
        </button>
    </div>
    
    <nav class="flex-grow-1 overflow-auto p-3">
        <div class="d-flex flex-column gap-1">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home sidebar-icon"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>
            
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tag sidebar-icon"></i>
                <span>{{ __('Categories') }}</span>
            </a>
            
            <a href="{{ route('admin.products.index') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box sidebar-icon"></i>
                <span>{{ __('Products') }}</span>
            </a>
            
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag sidebar-icon"></i>
                <span>{{ __('Orders') }}</span>
            </a>
            
            <a href="{{ route('admin.services.index') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase sidebar-icon"></i>
                <span>{{ __('Services') }}</span>
            </a>
            
            <a href="{{ route('admin.meetings.index') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.meetings.*') ? 'active' : '' }}">
                <i class="fas fa-calendar sidebar-icon"></i>
                <span>{{ __('Meetings') }}</span>
            </a>
            
            <a href="{{ route('admin.contacts.index') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                <i class="fas fa-envelope sidebar-icon"></i>
                <span>{{ __('Contacts') }}</span>
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="sidebar-link d-flex align-items-center px-3 py-2 text-decoration-none rounded-3 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users sidebar-icon"></i>
                <span>{{ __('Users') }}</span>
            </a>
        </div>
    </nav>
    
    <div class="mt-auto p-3 border-top border-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-link btn w-100 d-flex align-items-center px-3 py-2 rounded-3" style="border: none; background: transparent;">
                <i class="fas fa-sign-out-alt sidebar-icon"></i>
                <span>{{ __('Logout') }}</span>
            </button>
        </form>
    </div>
</aside>

<style>
    /* Sidebar base styles */
    .admin-sidebar {
        transition: transform 0.3s ease;
    }
    
    /* Sidebar hidden state */
    [dir="ltr"] .admin-sidebar.sidebar-hidden {
        transform: translateX(-100%);
    }
    
    [dir="rtl"] .admin-sidebar.sidebar-hidden {
        transform: translateX(100%);
    }
    
    /* Sidebar link styles */
    .sidebar-link {
        color: rgba(255, 255, 255, 0.5);
        transition: all 0.2s;
    }
    
    .sidebar-link:hover,
    .sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        color: white !important;
    }
    
    .sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.15);
    }
    
    /* Icon spacing based on direction */
    [dir="ltr"] .sidebar-icon {
        margin-right: 12px;
    }
    
    [dir="rtl"] .sidebar-icon {
        margin-left: 12px;
    }
</style>
