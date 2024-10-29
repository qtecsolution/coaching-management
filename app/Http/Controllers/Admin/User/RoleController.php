<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('view_roles')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::paginate(20);

        return view('admin.user.roles', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create_role')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        if (Role::create($request->only('name'))) {
            toast('Role created successfully.', 'success');
            return back();
        } else {
            toast('Something went wrong.', 'error');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('update_role')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => "required|unique:roles,name," . $id
        ]);

        $role = Role::findOrFail($id);

        if ($role->id == 1) {
            toast('Cannot update admin role.', 'error');
            return back();
        }

        $role->update([
            'name' => $request->name
        ]);

        toast('Role updated successfully.', 'success');
        return back();
    }

    public function show($id)
    {
        if (!Auth::user()->can('role_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $this->rolePermissions($id)->pluck('id')->toArray();

        return view('admin.user.role-permissions', compact('permissions', 'role', 'rolePermissions'));
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('delete_role')) {
            abort(403, 'Unauthorized action.');
        }

        if ($id != 1) {
            $role = Role::findOrFail($id);
            $role->delete();

            return true;
        } else {
            throw new Exception('Cannot delete default role.');
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        if (!Auth::user()->can('update_permission')) {
            abort(403, 'Unauthorized action.');
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::findOrFail($id);
        if ($role->id == 1) {
            $role->syncPermissions(Permission::all());

            toast($role->name . ' permissions are not editable.', 'info');
            return back();
        }
        
        $permissions = $request->get('permissions', []);
        $role->syncPermissions($permissions);

        toast('Permissions has been updated.', 'success');
        return back();
    }

    protected function rolePermissions($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions;

        return $permissions;
    }
}
