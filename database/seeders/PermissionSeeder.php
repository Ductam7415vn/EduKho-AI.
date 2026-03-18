<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        foreach (Permission::getDefaultPermissions() as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Assign all permissions to admin role
        $adminPermissions = Permission::all()->pluck('id');
        foreach ($adminPermissions as $permissionId) {
            DB::table('role_permissions')->insertOrIgnore([
                'role' => 'admin',
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign basic permissions to teacher role
        $teacherPermissions = Permission::whereIn('name', [
            'equipment.view',
            'borrow.view',
            'borrow.create',
            'borrow.return',
        ])->pluck('id');

        foreach ($teacherPermissions as $permissionId) {
            DB::table('role_permissions')->insertOrIgnore([
                'role' => 'teacher',
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
