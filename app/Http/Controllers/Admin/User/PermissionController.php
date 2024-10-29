<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('view_permissions')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions = Permission::all();
        return view('admin.user.permissions', compact('permissions'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create_permission')) {
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

            toast('Permission created successfully.', 'success');
        } else {
            Permission::create(['name' => 'view_' . $name]);
            Permission::create(['name' => 'add_' . $name]);
            Permission::create(['name' => 'update_' . $name]);
            Permission::create(['name' => 'delete_' . $name]);
            
            toast('Permissions created successfully.', 'success');
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('update_permission')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => "required|unique:permissions,name," . $id
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => Str::slug($request->name, '_'),
        ]);

        toast('Permission updated successfully.', 'success');
        return back();
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('delete_permission')) {
            abort(403, 'Unauthorized action.');
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return true;
    }
}
