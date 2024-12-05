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
            'view_users', 'create_user', 'update_user', 'delete_user',
            'view_roles', 'create_role', 'update_role', 'delete_role', 'role_permissions',
            'view_permissions', 'create_permission', 'update_permission', 'delete_permission',
            'view_students', 'create_student', 'update_student', 'delete_student',
            'view_batches', 'create_batch', 'update_batch', 'delete_batch',
            'view_leads', 'create_lead', 'update_lead', 'delete_lead',
            'view_payments','create_payment','update_payment','delete_payment',
            'view_settings', 'update_settings',
            'view_class_materials', 'create_class_material', 'update_class_material', 'delete_class_material',
            'view_attendance', 'create_attendance', 'update_attendance', 'delete_attendance'
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
