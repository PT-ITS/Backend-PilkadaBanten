<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // pengurus
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345'),
            'created_at' => Carbon::create(2024, 10, 3, 12, 0, 0), // Format: (year, month, day, hour, minute, second)
            'updated_at' => Carbon::create(2024, 10, 3, 12, 0, 0) // Format: (year, month, day, hour, minute, second)
        ]);
    }
}
