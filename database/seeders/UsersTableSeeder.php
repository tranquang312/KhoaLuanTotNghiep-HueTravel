<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'bio' => $faker->text(100),
            'created_at' => now(),
            'updated_at' => now(),
        ])->assignRole('admin');
        // Create 50 random users
        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                //email verified between 1 year ago and now
                'email_verified_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'bio' => $faker->text(100),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'password' => Hash::make('password'),
            ]);
            // Assign roles to users
            $user = User::latest()->first();
            $user->assignRole('user');
        }
    }
}
