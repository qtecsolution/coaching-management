<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Admin']);

        $user = User::find(1);
        $user->syncRoles($role);

        // create permissions
        $permissions = [
            'view_users',
            'create_user',
            'update_user',
            'delete_user',
            'view_roles',
            'create_role',
            'update_role',
            'delete_role',
            'role_permissions',
            'view_permissions',
            'create_permission',
            'update_permission',
            'delete_permission',
            'view_students',
            'create_student',
            'update_student',
            'delete_student'
        ];

        $permissions = collect($permissions)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()];
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::insert($permissions->toArray());

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $role->syncPermissions(Permission::all());
    }
}
