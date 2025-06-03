<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $role = Role::firstOrCreate(['name' => 'guide', 'guard_name' => 'web']);
        User::factory(5)->create()->each(function ($user) use ($role) {
            $user->assignRole($role);
        });
    }
}
