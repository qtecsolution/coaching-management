<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        {{-- d-flex justify-content-between align-items-center --}}
        <div class="d-block text-center">
            <div class="logo">
                @php
                    $logo = asset('assets/static/images/logo/logo.svg');
                    $logoSetting = \App\Models\Setting::where('key', 'app_logo')->value('value');

                    if ($logoSetting) {
                        $logo = asset('storage/' . $logoSetting);
                    }
                @endphp
                <a href="{{ url('/') }}"><img src="{{ $logo }}" alt="Logo"></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul class="menu">
            @if (in_array(auth()->user()->user_type, ['admin', 'teacher']))
                @include('layouts.partials.admin-nav')
            @else
                @include('layouts.partials.user-nav')
            @endif

            <li class="sidebar-item mt-4 {{ Route::is('auth.profile') ? 'active' : '' }}">
                <a href="{{ route('auth.profile') }}" class="sidebar-link">
                    <i class="bi bi-person-badge"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('auth.logout') }}" class="sidebar-link">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</div>
