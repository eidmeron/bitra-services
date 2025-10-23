<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Service;
use App\Models\City;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class SampleRevenueDataSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing services, cities, and companies
        $services = Service::where('status', 'active')->get();
        $cities = City::where('status', 'active')->get();
        $companies = Company::where('status', 'active')->get();
        $users = User::where('type', 'user')->get();

        if ($services->isEmpty() || $cities->isEmpty() || $companies->isEmpty() || $users->isEmpty()) {
            $this->command->info('No services, cities, companies, or users found. Please run other seeders first.');
            return;
        }

        $this->command->info('Creating sample revenue data...');

        // Create bookings for the last 6 months with different statuses
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            
            // Create 10-20 bookings per month
            $bookingsCount = rand(10, 20);
            
            for ($j = 0; $j < $bookingsCount; $j++) {
                $service = $services->random();
                $city = $cities->random();
                $company = $companies->random();
                $user = $users->random();
                
                // Random date within the month
                $bookingDate = $month->copy()->addDays(rand(1, $month->daysInMonth));
                
                // Random status with higher probability for completed bookings
                $statuses = ['completed', 'completed', 'completed', 'confirmed', 'confirmed', 'pending', 'cancelled'];
                $status = $statuses[array_rand($statuses)];
                
                // Calculate price based on service
                $basePrice = $service->base_price;
                $finalPrice = $basePrice + rand(0, 500); // Add some variation
                
                $booking = Booking::create([
                    'booking_number' => 'BK' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'service_id' => $service->id,
                    'city_id' => $city->id,
                    'form_id' => 1, // Default form ID
                    'customer_type' => 'private',
                    'booking_type' => 'one_time',
                    'status' => $status,
                    'customer_name' => $user->name ?: 'Test User',
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? '+46' . rand(700000000, 799999999),
                    'preferred_date' => $bookingDate->format('Y-m-d'),
                    'customer_message' => 'Test booking for revenue data',
                    'form_data' => json_encode(['test' => 'data']),
                    'base_price' => $basePrice,
                    'city_multiplier' => 1.0,
                    'subscription_multiplier' => 1.0,
                    'variable_additions' => 0,
                    'discount_amount' => 0,
                    'rot_deduction' => 0,
                    'final_price' => $finalPrice,
                    'tax_amount' => $finalPrice * 0.25, // 25% tax
                    'subtotal' => $finalPrice * 0.75, // Before tax
                    'total_with_tax' => $finalPrice,
                    'tax_rate' => 0.25,
                    'created_at' => $bookingDate,
                    'updated_at' => $bookingDate,
                ]);
                
                // If completed, set completed_at
                if ($status === 'completed') {
                    $booking->update([
                        'completed_at' => $bookingDate->copy()->addHours(rand(1, 8))
                    ]);
                }
            }
        }

        $this->command->info('Sample revenue data created successfully!');
        $this->command->info('Total bookings created: ' . Booking::count());
        $this->command->info('Total revenue: ' . number_format(Booking::whereIn('status', ['completed', 'confirmed'])->sum('final_price'), 0, ',', ' ') . ' kr');
    }
}