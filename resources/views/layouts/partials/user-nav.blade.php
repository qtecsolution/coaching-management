<ul class="menu">
    <li class="sidebar-item {{ Route::is('user.dashboard') ? 'active' : '' }}">
        <a href="{{ route('user.dashboard') }}" class="sidebar-link">
            <i class="bi bi-house-door"></i>
            <span>Dashboard</span>
        </a>
    </li>

    {{-- <li class="sidebar-item has-sub">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-people"></i>
            <span>Users</span>
        </a>
        <ul class="submenu submenu-close">
            <li class="submenu-item">
                <a href="#">User List</a>
            </li>
            <li class="submenu-item">
                <a href="#">Add User</a>
            </li>
            <li class="submenu-item">
                <a href="#">Role & Permission</a>
            </li>
        </ul>
    </li> --}}
    <li class="sidebar-item {{ Route::is('user.payments.index') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('user.payments.index') }}"> <i class="bi bi-cash-stack"></i>
            <span>Payment History</span></a>
    </li>
    <li class="sidebar-item {{ Route::is('user.payments.due') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('user.payments.due') }}"> <i class="bi bi-cash-stack"></i>
            <span>Payment Due</span></a>
    </li>
    <li class="sidebar-item {{ Route::is('auth.profile') ? 'active' : '' }}">
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