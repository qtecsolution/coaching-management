<li class="sidebar-item {{ Route::is('user.dashboard') ? 'active' : '' }}">
    <a href="{{ route('user.dashboard') }}" class="sidebar-link">
        <i class="bi bi-house-door"></i>
        <span>Dashboard</span>
    </a>
</li>

<li class="sidebar-item {{ Route::is('user.class-materials.index') ? 'active' : '' }}">
    <a href="{{ route('user.class-materials.index') }}" class="sidebar-link">
        <i class="bi bi-collection"></i>
        <span>Class Materials</span>
    </a>
</li>

<li class="sidebar-item {{ Route::is('user.payments.index') ? 'active' : '' }}">
    <a href="{{ route('user.payments.index') }}" class="sidebar-link">
        <i class="bi bi-cash-coin"></i>
        <span>Payments</span>
    </a>
</li>
