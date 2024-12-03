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
        @if (in_array(auth()->user()->user_type, ['admin', 'teacher']))
            @include('layouts.partials.admin-nav')
        @else
            @include('layouts.partials.user-nav')
        @endif
    </div>
</div>
