<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use App\Traits\ExceptionHandler;

class PermissionController extends Controller
{
    use ExceptionHandler;

    public function index()
    {
        if (!auth()->user()->can('view_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        return view('admin.user.permissions', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create_permission')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|unique:permissions,name',
            'type' => 'required'
        ]);

        $name = Str::slug($request->name, '_');

        if ($request->type == 'single') {
            Permission::create([
                'name' => $name
            ]);

            $this->getAlert('success', 'Permission added successfully.');
        } else {
            Permission::create(['name' => 'view_' . $name]);
            Permission::create(['name' => 'add_' . $name]);
            Permission::create(['name' => 'update_' . $name]);
            Permission::create(['name' => 'delete_' . $name]);

            $this->getAlert('success', 'Permissions added successfully.');
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('update_permission')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => "required|unique:permissions,name," . $id
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => Str::slug($request->name, '_'),
        ]);

        $this->getAlert('success', 'Permission updated successfully.');
        return back();
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete_permission')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return true;
        } catch (\Throwable $th) {
            $this->logException($th);
            throw new Exception($th->getMessage());
        }
    }
}
