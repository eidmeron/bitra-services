<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample regular users
        $users = [
            [
                'email' => 'user1@example.com',
                'phone' => '0701-234567',
            ],
            [
                'email' => 'user2@example.com',
                'phone' => '0702-345678',
            ],
            [
                'email' => 'user3@example.com',
                'phone' => '0703-456789',
            ],
            [
                'email' => 'anna.andersson@example.com',
                'phone' => '0704-567890',
            ],
            [
                'email' => 'erik.eriksson@example.com',
                'phone' => '0705-678901',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'type' => 'user',
                    'phone' => $userData['phone'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                ]
            );
        }

        $this->command->info('Created ' . count($users) . ' regular users');
    }
}

