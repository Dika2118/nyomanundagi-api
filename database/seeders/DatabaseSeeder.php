<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin default
        User::updateOrCreate(
            ['email' => 'admin@nyomanundagi.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Test user
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Admin example
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Example',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
