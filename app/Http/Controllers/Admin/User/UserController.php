<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // function to show users
    public function index()
    {
        if (!auth()->user()->can('view_users')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $users = User::where('user_type', request()->user_type)
                ->latest();

            return DataTables::of($users)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        return '<span class="badge bg-primary">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->editColumn('email', function ($row) {
                    return $row->email ? $row->email : '--';
                })
                ->addColumn('role', function ($row) {
                    $roles = $row->getRoleNames();
                    return isset($roles[0]) ? '<span class="badge bg-secondary">' . $roles[0] . '</span>' : '--';
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
        if (!auth()->user()->can('create_user')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    // function to store user
    public function store(Request $request)
    {
        if (!auth()->user()->can('create_user')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'nullable|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'user_type' => 'required|in:admin,teacher',
            'school_name' => 'required_if:user_type,teacher',
            'nid_number' => 'required_if:user_type,teacher',
            'address' => 'required_if:user_type,teacher',
            'contact_name' => 'required_if:user_type,teacher',
            'contact_phone' => 'required_if:user_type,teacher',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'user_type' => $request->user_type
            ]);

            if ($request->user_type == 'teacher') {
                Teacher::create([
                    'user_id' => $user->id,
                    'teacher_id' => rand(1000, 9999) . $user->id,
                    'school_name' => $request->school_name,
                    'nid_number' => $request->nid_number,
                    'address' => $request->address,
                    'emergency_contact' => json_encode([
                        'name' => $request->contact_name,
                        'phone' => $request->contact_phone
                    ])
                ]);
            }

            if ($request->has('role')) {
                $user->assignRole($request->role);
            }

            alert('Yahoo!', 'User added successfully.', 'success');
            return to_route('admin.users.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
            return back();
        }
    }

    // function to show edit user form
    public function edit(User $user)
    {
        if (!auth()->user()->can('update_user')) {
            abort(403, 'Unauthorized action.');
        }

        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    // Function to update user
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->can('update_user')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id,
            'email' => 'nullable|unique:users,email,' . $user->id,
            'password' => 'nullable',
            'role' => 'required',
            'status' => 'required|boolean',
            'user_type' => 'required|in:admin,teacher',
            'school_name' => 'required_if:user_type,teacher',
            'nid_number' => 'required_if:user_type,teacher',
            'address' => 'required_if:user_type,teacher',
            'contact_name' => 'required_if:user_type,teacher',
            'contact_phone' => 'required_if:user_type,teacher',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
                'user_type' => $request->user_type,
                'status' => $request->status,
            ]);

            if ($request->user_type == 'teacher') {
                if ($user->teacher) {
                    $user->teacher->update([
                        'school_name' => $request->school_name,
                        'nid_number' => $request->nid_number,
                        'address' => $request->address,
                        'emergency_contact' => json_encode([
                            'name' => $request->contact_name,
                            'phone' => $request->contact_phone
                        ])
                    ]);
                } else {
                    Teacher::create([
                        'user_id' => $user->id,
                        'teacher_id' => rand(1000, 9999) . $user->id,
                        'school_name' => $request->school_name,
                        'nid_number' => $request->nid_number,
                        'address' => $request->address,
                        'emergency_contact' => json_encode([
                            'name' => $request->contact_name,
                            'phone' => $request->contact_phone
                        ])
                    ]);
                }
            } else {
                if ($user->teacher) {
                    $user->teacher->delete();
                }
            }

            if ($request->filled('role')) {
                $user->syncRoles($request->role);
            }

            alert('Yahoo!', 'User updated successfully.', 'success');
            return to_route('admin.users.index');
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());

            alert('Oops!', 'Something went wrong.', 'error');
            return back();
        }
    }

    // function to delete user
    public function destroy(User $user)
    {
        if (!auth()->user()->can('delete_user')) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->id == 1) {
            return false;
        }

        try {
            $user->delete();
            return true;
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' on line ' . $th->getLine() . ' in file ' . $th->getFile());
            return false;
        }
    }
}
