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

    <li class="sidebar-item has-sub {{ Route::is('admin.batches.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-easel"></i>
            <span>Batches</span>
        </a>
        <ul class="submenu {{ Route::is('admin.batches.*') ? 'submenu-open' : 'submenu-close' }}">
            <li class="submenu-item {{ Route::is('admin.batches.index') ? 'active' : '' }}">
                <a href="{{ route('admin.batches.index') }}">Batch List</a>
            </li>
            <li class="submenu-item {{ Route::is('admin.batches.create') ? 'active' : '' }}">
                <a href="{{ route('admin.batches.create') }}">Add Batch</a>
            </li>
        </ul>
    </li>
    
    <li class="sidebar-item has-sub {{ Route::is('admin.class-materials.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-collection"></i>
            <span>Class Materials</span>
        </a>
        <ul class="submenu {{ Route::is('admin.class-materials.*') ? 'submenu-open' : 'submenu-close' }}">
            <li class="submenu-item {{ Route::is('admin.class-materials.index') ? 'active' : '' }}">
                <a href="{{ route('admin.class-materials.index') }}">Material List</a>
            </li>
            <li class="submenu-item {{ Route::is('admin.class-materials.create') ? 'active' : '' }}">
                <a href="{{ route('admin.class-materials.create') }}">Add Material</a>
            </li>
        </ul>
    </li>

    <li class="sidebar-item has-sub {{ Route::is('admin.leads.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-clipboard2-data"></i>
            <span>Leads</span>
        </a>
        <ul class="submenu {{ Route::is('admin.leads.*') ? 'submenu-open' : 'submenu-close' }}">
            <li class="submenu-item {{ Route::is('admin.leads.index') ? 'active' : '' }}">
                <a href="{{ route('admin.leads.index') }}">Lead List</a>
            </li>
            <li class="submenu-item {{ Route::is('admin.leads.create') ? 'active' : '' }}">
                <a href="{{ route('admin.leads.create') }}">Add Lead</a>
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

    <li class="sidebar-item mt-4">
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
