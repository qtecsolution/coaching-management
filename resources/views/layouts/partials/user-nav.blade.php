<li class="sidebar-item {{ Route::is('user.dashboard') ? 'active' : '' }}">
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">
        <i class="bi bi-house-door"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="sidebar-item has-sub {{ Route::is('user.payments.*') ? 'active' : '' }}">
    <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
        <i class="bi bi-cash-stack"></i>
        <span>Tuition Fee</span>
    </a>
    <ul class="submenu {{ Route::is('user.payments.*') ? 'submenu-open' : 'submenu-close' }}">
        <li class="submenu-item {{ Route::is('user.payments.index') ? 'active' : '' }}">
            <a href="{{ route('user.payments.index') }}">Payment History</a>
        </li>
        <li class="submenu-item {{ Route::is('user.payments.due') ? 'active' : '' }}">
            <a href="{{ route('user.payments.due') }}">Payment Due</a>
        </li>
    </ul>
</li>
