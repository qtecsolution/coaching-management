<?php

namespace Database\Seeders;

use App\Models\User;
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
        // create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $teacherRole = Role::create(['name' => 'Teacher']);

        // assign roles to users
        $admin = User::find(1);
        $admin->syncRoles($adminRole);

        $teachers = User::where('user_type', 'teacher')->get();
        $teachers->each(function ($teacher) use ($teacherRole) {
            $teacher->syncRoles($teacherRole);
        });

        // create permissions
        $permissions = [
            'view_users', 'create_user', 'update_user', 'delete_user',
            'view_roles', 'create_role', 'update_role', 'delete_role', 'role_permissions',
            'view_permissions', 'create_permission', 'update_permission', 'delete_permission',
            'view_students', 'create_student', 'update_student', 'delete_student',
            'view_courses', 'create_course', 'update_course', 'delete_course',
            'view_batches', 'create_batch', 'update_batch', 'delete_batch',
            'view_leads', 'create_lead', 'update_lead', 'delete_lead',
            'view_payments','create_payment','update_payment','delete_payment',
            'view_reports','paid_reports','due_reports','summary_reports',
            'view_settings', 'update_settings',
            'view_class_materials', 'create_class_material', 'update_class_material', 'delete_class_material',
            'view_attendance', 'create_attendance', 'update_attendance', 'delete_attendance',
            'view_messages', 'create_message', 'update_message', 'delete_message',
        ];

        $teacherPermissions = [
            'view_students', 'view_batches',
            'view_attendance', 'create_attendance', 'update_attendance', 'delete_attendance',
            'view_messages', 'create_message', 'update_message', 'delete_message',
        ];

        $permissions = collect($permissions)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()];
        });

        // forget cached permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::insert($permissions->toArray());

        // sync permissions
        $adminRole->syncPermissions(Permission::all());
        $teacherRole->syncPermissions($teacherPermissions);
    }
}
