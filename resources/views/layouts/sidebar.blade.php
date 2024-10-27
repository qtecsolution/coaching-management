<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        {{-- d-flex justify-content-between align-items-center --}}
        <div class="d-block text-center">
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{ asset('assets/static/images/logo/logo.svg') }}" alt="Logo"></a>
            </div>
        </div>
    </div>
    <div class="sidebar-menu">
        @if (in_array(auth()->user()->user_type, ['admin', 'teacher']))
            @include('layouts.partials.admin-nav')
        @else
            @include('layouts.partials.user-nav')
        @endif
    </div>
</div>
