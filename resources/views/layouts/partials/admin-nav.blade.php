<ul class="menu">
    <li class="sidebar-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
            <i class="bi bi-house-door"></i>
            <span>Dashboard</span>
        </a>
    </li>


    <li class="sidebar-item has-sub {{ Route::is('admin.users.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-people"></i>
            <span>Users</span>
        </a>
        <ul class="submenu {{ Route::is('admin.users.*') ? 'submenu-open' : 'submenu-close' }}">
            <li class="submenu-item {{ Route::is('admin.users.index') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">User List</a>
            </li>
            <li class="submenu-item {{ Route::is('admin.users.create') ? 'active' : '' }}">
                <a href="{{ route('admin.users.create') }}">Add User</a>
            </li>
            <li class="submenu-item {{ Route::is('admin.users.roles.index') ? 'active' : '' }}">
                <a href="{{ route('admin.users.roles.index') }}">Role & Permission</a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item has-sub {{ Route::is('admin.students.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-person-plus"></i>
            <span>Students</span>
        </a>
        <ul class="submenu {{ Route::is('admin.students.*') ? 'submenu-open' : 'submenu-close' }}">
            <li class="submenu-item {{ Route::is('admin.students.index') ? 'active' : '' }}">
                <a href="{{ route('admin.students.index') }}">Student List</a>
            </li>
            <li class="submenu-item {{ Route::is('admin.students.create') ? 'active' : '' }}">
                <a href="{{ route('admin.students.create') }}">Add Student</a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item has-sub {{ Route::is('admin.settings.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
        <ul class="submenu {{ Route::is('admin.settings.*') ? 'submenu-open' : 'submenu-close' }}">
            <li
                class="submenu-item {{ Route::currentRouteName() === 'admin.settings.edit' && request()->segment(3) === 'general' ? 'active' : '' }}">
                <a href="{{ route('admin.settings.edit', 'general') }}">General Settings</a>
            </li>
            <li
                class="submenu-item {{ Route::currentRouteName() === 'admin.settings.edit' && request()->segment(3) === 'smtp' ? 'active' : '' }}">
                <a href="{{ route('admin.settings.edit', 'smtp') }}">SMTP Settings</a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item">
        <a href="{{ route('auth.logout') }}" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i>
            <span>Log Out</span>
        </a>
    </li>
</ul>
