<li class="sidebar-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <i class="bi bi-house-door"></i>
        <span>Dashboard</span>
    </a>
</li>

@can('view_users')
    <li class="sidebar-item has-sub {{ Route::is('admin.users.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-people"></i>
            <span>Users</span>
        </a>
        <ul class="submenu {{ Route::is('admin.users.*') ? 'submenu-open' : 'submenu-close' }}">
            @can('view_users')
                <li class="submenu-item {{ Route::is('admin.users.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">User List</a>
                </li>
            @endcan
            @can('create_user')
                <li class="submenu-item {{ Route::is('admin.users.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.create') }}">Add User</a>
                </li>
            @endcan
            @can('view_roles')
                <li class="submenu-item {{ Route::is('admin.users.roles.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.roles.index') }}">Role & Permission</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('view_students')
    <li class="sidebar-item has-sub {{ Route::is('admin.students.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-person-plus"></i>
            <span>Students</span>
        </a>
        <ul class="submenu {{ Route::is('admin.students.*') ? 'submenu-open' : 'submenu-close' }}">
            @can('view_students')
                <li class="submenu-item {{ Route::is('admin.students.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.students.index') }}">Student List</a>
                </li>
            @endcan
            @can('create_student')
                <li class="submenu-item {{ Route::is('admin.students.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.students.create') }}">Add Student</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('view_batches')
    <li class="sidebar-item has-sub {{ Route::is('admin.batches.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-easel"></i>
            <span>Batches</span>
        </a>
        <ul class="submenu {{ Route::is('admin.batches.*') ? 'submenu-open' : 'submenu-close' }}">
            @can('view_batches')
                <li class="submenu-item {{ Route::is('admin.batches.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.batches.index') }}">Batch List</a>
                </li>
            @endcan
            @can('create_batch')
                <li class="submenu-item {{ Route::is('admin.batches.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.batches.create') }}">Add Batch</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('view_attendance')
    <li class="sidebar-item {{ Route::is('admin.attendance.index') ? 'active' : '' }}">
        <a href="{{ route('admin.attendance.index') }}" class="sidebar-link">
            <i class="bi bi-calendar3"></i>
            <span>Attendance</span>
        </a>
    </li>
@endcan

@can('view_class_materials')
    <li class="sidebar-item has-sub {{ Route::is('admin.class-materials.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-collection"></i>
            <span>Class Materials</span>
        </a>
        <ul class="submenu {{ Route::is('admin.class-materials.*') ? 'submenu-open' : 'submenu-close' }}">
            @can('view_class_materials')
                <li class="submenu-item {{ Route::is('admin.class-materials.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.class-materials.index') }}">Material List</a>
                </li>
            @endcan
            @can('create_class_material')
                <li class="submenu-item {{ Route::is('admin.class-materials.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.class-materials.create') }}">Add Material</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('view_leads')
    <li class="sidebar-item has-sub {{ Route::is('admin.leads.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-clipboard2-data"></i>
            <span>Leads</span>
        </a>
        <ul class="submenu {{ Route::is('admin.leads.*') ? 'submenu-open' : 'submenu-close' }}">
            @can('view_leads')
                <li class="submenu-item {{ Route::is('admin.leads.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.leads.index') }}">Lead List</a>
                </li>
            @endcan
            @can('create_lead')
                <li class="submenu-item {{ Route::is('admin.leads.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.leads.create') }}">Add Lead</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan

@can('view_payments')
    <li class="sidebar-item has-sub {{ Route::is('admin.payments.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-cash-stack"></i>
            <span>Tuition Fee</span>
        </a>
        <ul class="submenu {{ Route::is('admin.payments.*') ? 'submenu-open' : 'submenu-close' }}">
            <li class="submenu-item {{ Route::is('admin.payments.index') ? 'active' : '' }}">
                <a href="{{ route('admin.payments.index') }}">Payment List</a>
            </li>
            @can('create_payment')
                <li class="submenu-item {{ Route::is('admin.payments.generate') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.generate') }}">Generate Payments</a>
                </li>
                <li class="submenu-item {{ Route::is('admin.payments.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.create') }}">Add Payment</a>
                </li>
            @endcan
            <li class="submenu-item {{ Route::is('admin.payments.due') ? 'active' : '' }}">
                <a href="{{ route('admin.payments.due') }}">Due Payments</a>
            </li>
        </ul>
    </li>
@endcan

@can('view_reports')
    <li class="sidebar-item has-sub {{ Route::is('admin.reports.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-cash-stack"></i>
            <span>Reports</span>
        </a>
        <ul class="submenu {{ Route::is('admin.reports.*') ? 'submenu-open' : 'submenu-close' }}">
            @can('paid_reports')
                <li class="submenu-item {{ Route::is('admin.reports.daily.collection') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.daily.collection') }}">Payments Paid</a>
                </li>
            @endcan
            @can('due_reports')
                <li class="submenu-item {{ Route::is('admin.reports.payments.due') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.payments.due') }}">Payments Due</a>
                </li>
            @endcan
            @can('summary_reports')
                <li class="submenu-item {{ Route::is('admin.reports.payments.summary') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.payments.summary') }}">Payments Summary</a>
                </li>
            @endcan
        </ul>
    </li>
@endcan
@can('view_settings')
    <li class="sidebar-item has-sub {{ Route::is('admin.settings.*') ? 'active' : '' }}">
        <a href="javascript:void(0)" class="sidebar-link toggle-submenu">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
        <ul class="submenu {{ Route::is('admin.settings.*') ? 'submenu-open' : 'submenu-close' }}">
            <li
                class="submenu-item {{ Route::currentRouteName() === 'admin.settings.edit' && request()->segment(3) === 'general-settings' ? 'active' : '' }}">
                <a href="{{ route('admin.settings.edit', 'general-settings') }}">General Settings</a>
            </li>
            <li
                class="submenu-item {{ Route::currentRouteName() === 'admin.settings.edit' && request()->segment(3) === 'email-settings' ? 'active' : '' }}">
                <a href="{{ route('admin.settings.edit', 'email-settings') }}">Email Settings</a>
            </li>
            <li
                class="submenu-item {{ Route::currentRouteName() === 'admin.settings.edit' && request()->segment(3) === 'sms-settings' ? 'active' : '' }}">
                <a href="{{ route('admin.settings.edit', 'sms-settings') }}">SMS Settings</a>
            </li>
        </ul>
    </li>
@endcan
