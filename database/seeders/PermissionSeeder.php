<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các quyền cơ bản
        $permissions = [
            'access-admin' => 'Truy cập trang quản trị',
            'manage-users' => 'Quản lý người dùng',
            'manage-roles' => 'Quản lý vai trò',
            'manage-permissions' => 'Quản lý quyền',
            'manage-tours' => 'Quản lý tour',
            'manage-destinations' => 'Quản lý điểm đến',
        ];

        foreach ($permissions as $name => $description) {
            Permission::create([
                'name' => $name,
                'description' => $description,
                'guard_name' => 'web'
            ]);
        }

        // Tạo role admin nếu chưa tồn tại
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Gán tất cả quyền cho role admin
        $adminRole->givePermissionTo(Permission::all());
    }
}
