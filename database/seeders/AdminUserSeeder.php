<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Saad Naanaiy',
            'email' => 'saadnaanaiy@gmail.com',
            'is_admin' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // You should change this in production
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
    }
}
