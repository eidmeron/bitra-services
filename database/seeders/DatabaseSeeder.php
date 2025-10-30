<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@bitratjanster.se'],
            [
                'type' => 'admin',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );

        $this->call([
            ZoneCitySeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            FormSeeder::class,
            PageContentSeeder::class,
            SeoPageSeeder::class,
        ]);
    }
}
