<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\User;
use Faker\Factory as Faker;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        // Get all tours and users
        $tours = Tour::all();
        $users = User::where('role', 'user')->get();
        
        // Status options
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];
        $paymentMethods = ['cash', 'bank_transfer', 'momo', 'vnpay'];

        // Create 20 random bookings
        for ($i = 0; $i < 20; $i++) {
            $tour = $tours->random();
            $user = $users->random();
            $people = $faker->numberBetween(1, 4);
            $children = $faker->numberBetween(0, 2);
            $adultPrice = $tour->price;
            $childPrice = $tour->price * 0.7; // Giả sử giá trẻ em bằng 70% giá người lớn
            $totalPrice = ($people * $adultPrice) + ($children * $childPrice);

            Booking::create([
                'tour_id' => $tour->id,
                'user_id' => $user->id,
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'people' => $people,
                'children' => $children,
                'total_price' => $totalPrice,
                'start_date' => $faker->dateTimeBetween('now', '+2 months'),
                'status' => $faker->randomElement($statuses),
                'payment_status' => $faker->randomElement($paymentStatuses),
                'payment_method' => $faker->randomElement($paymentMethods),
                'note' => $faker->optional(0.7)->sentence(), // 70% khả năng có ghi chú
                'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
