<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Traits\ExceptionHandler;

class RoleController extends Controller
{
    use ExceptionHandler;

    public function index()
    {
        if (!auth()->user()->can('view_roles')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::paginate(20);
        return view('admin.user.roles', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create_role')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        if (Role::create($request->only('name'))) {
            $this->getAlert('success', 'Role created successfully.');
        } else {
            $this->getAlert('error', 'Something went wrong.');
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('update_role')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => "required|unique:roles,name," . $id
        ]);

        $role = Role::findOrFail($id);

        if ($role->id == 1) {
            $this->getAlert('info', 'You cannot update admin role.');
            return back();
        }

        $role->update([
            'name' => $request->name
        ]);

        $this->getAlert('success', 'Role updated successfully.');
        return back();
    }

    public function show($id)
    {
        if (!auth()->user()->can('role_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $this->rolePermissions($id)->pluck('id')->toArray();

        return view('admin.user.role-permissions', compact('permissions', 'role', 'rolePermissions'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete_role')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            if ($id != 1) {
                $role = Role::findOrFail($id);
                $role->delete();

                return true;
            } else {
                throw new Exception('You cannot delete admin role.');
            }
        } catch (\Throwable $th) {
            $this->logException($th);
            throw new Exception($th->getMessage());
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        if (!auth()->user()->can('update_permission')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $role = Role::findOrFail($id);
            if ($role->id == 1) {
                $role->syncPermissions(Permission::all());

                $this->getAlert('info', $role->name . ' permissions are not editable.');
                return back();
            }

            $permissions = $request->get('permissions', []);
            $role->syncPermissions($permissions);

            $this->getAlert('success', 'Permissions has been updated.');
            return back();
        } catch (\Throwable $th) {
            $this->logException($th);
            $this->getAlert('error', 'Something went wrong.');

            return back();
        }
    }

    protected function rolePermissions($id)
    {
        $role = Role::findOrFail($id);
        $permissions = $role->permissions;

        return $permissions;
    }
}
