<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            alert('Yahoo!', 'Role added successfully.', 'success');
            return back();
        } else {
            alert('Oops!', 'Something went wrong.', 'error');
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
            alert('Oops!', 'Cannot update admin role.', 'error');
            return back();
        }

        $role->update([
            'name' => $request->name
        ]);

        alert('Yahoo!', 'Role updated successfully.', 'success');
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
            throw new Exception('You can not delete admin role.');
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        if (!Auth::user()->can('update_permission')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $role = Role::findOrFail($id);
            if ($role->id == 1) {
                $role->syncPermissions(Permission::all());

                alert('Hey!', $role->name . ' permissions are not editable.', 'info');
                return back();
            }

            $permissions = $request->get('permissions', []);
            $role->syncPermissions($permissions);

            alert('Yahoo!', 'Permissions has been updated.', 'success');
            return back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
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
