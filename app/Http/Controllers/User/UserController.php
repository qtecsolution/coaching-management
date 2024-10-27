<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // function to show users
    public function index()
    {
        if (request()->ajax()) {
            $users = User::where('user_type', 'admin');
            return DataTables::of($users)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge bg-primary">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('role', function ($row) {
                    $roles = $row->getRoleNames();
                    return '<span class="badge bg-secondary">' . $roles[0] . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.user.action', compact('row'));
                })
                ->rawColumns(['status', 'role'])
                ->make(true);
        }

        return view('admin.user.index');
    }

    // function to show create user form
    public function create()
    {
        $roles = Role::all();

        return view('admin.user.create', compact('roles'));
    }

    // function to store user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'nullable|unique:users,email',
            'password' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_admin' => $request->is_admin,
        ]);

        if ($request->has('role')) {
            $user->assignRole($request->role);
        }

        toast('User added successfully.', 'success');
        return to_route('admin.users.index');
    }

    // function to show edit user form
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    // function to update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id,
            'email' => 'nullable|unique:users,email,' . $user->id,
            'password' => 'nullable',
            'role' => 'required',
            'status' => 'required|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'is_admin' => $request->is_admin,
            'status' => $request->status,
        ]);

        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        toast('User updated successfully.', 'success');
        return to_route('admin.users.index');
    }

    // function to delete user
    public function destroy(User $user)
    {
        $user->delete();
        return true;
    }
}
