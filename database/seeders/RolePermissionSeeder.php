<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // إعادة ضبط الكاش
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الصلاحيات
        $permissions = [
            'manage patients',
            'manage appointments',
            'manage visits',
            'manage lab requests',
            'manage lab results',
            'enter lab results',
            'manage prescriptions',
            'manage finances',
            'view reports',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // دور الطبيب
        $doctorRole = Role::findOrCreate('doctor', 'web');
        $doctorRole->syncPermissions([
            'manage patients',
            'manage visits',
            'manage lab requests',
            'manage prescriptions',
            'view reports',
        ]);

        // دور الممرض
        $nurseRole = Role::findOrCreate('nurse', 'web');
        $nurseRole->syncPermissions([
            'manage patients',
            'manage appointments',
            'manage lab results',
            'enter lab results',
            'manage finances',
            'view reports',
        ]);

        // دور المدير
        $adminRole = Role::findOrCreate('admin', 'web');
        $adminRole->syncPermissions(Permission::all());

        echo "✅ تم إنشاء الأدوار: doctor, nurse, admin\n";
        echo "✅ تم إنشاء " . count($permissions) . " صلاحية\n";
    }
}
