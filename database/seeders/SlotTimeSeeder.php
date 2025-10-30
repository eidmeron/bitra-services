<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SlotTime;
use App\Models\Service;
use App\Models\City;
use App\Models\Company;
use Carbon\Carbon;

class SlotTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some services, cities, and companies
        $services = Service::where('status', 'active')->take(3)->get();
        $cities = City::where('status', 'active')->take(3)->get();
        $companies = Company::where('status', 'active')->take(2)->get();

        if ($services->isEmpty() || $cities->isEmpty()) {
            $this->command->info('No services or cities found. Please seed services and cities first.');
            return;
        }

        $slotTimes = [];

        // Create slot times for the next 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->addDays($i);
            
            // Skip weekends for some services
            if ($date->isWeekend() && $i % 3 === 0) {
                continue;
            }

            foreach ($services as $service) {
                foreach ($cities as $city) {
                    // Create 2-4 time slots per day
                    $timeSlots = [
                        ['09:00', '10:00'],
                        ['10:00', '11:00'],
                        ['14:00', '15:00'],
                        ['15:00', '16:00'],
                    ];

                    // Randomly select 2-4 time slots
                    $selectedSlots = collect($timeSlots)->random(rand(2, 4));

                    foreach ($selectedSlots as $slot) {
                        // Sometimes assign to specific company, sometimes leave open for all
                        $companyId = $companies->isNotEmpty() && rand(0, 1) ? $companies->random()->id : null;

                        $slotTimes[] = [
                            'service_id' => $service->id,
                            'city_id' => $city->id,
                            'company_id' => $companyId,
                            'date' => $date->format('Y-m-d'),
                            'start_time' => $slot[0],
                            'end_time' => $slot[1],
                            'capacity' => rand(1, 5),
                            'booked_count' => rand(0, 2), // Some slots already have bookings
                            'is_available' => rand(0, 10) > 1, // 90% available
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Insert in batches
        collect($slotTimes)->chunk(100)->each(function ($chunk) {
            SlotTime::insert($chunk->toArray());
        });

        $this->command->info('Created ' . count($slotTimes) . ' slot times.');
    }
}
