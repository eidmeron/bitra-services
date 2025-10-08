<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ZoneCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create zones and cities for Sweden
        $zones = [
            [
                'name' => 'Stor-Stockholm',
                'slug' => 'stor-stockholm',
                'description' => 'Stockholms län och närliggande områden',
                'status' => 'active',
                'cities' => [
                    ['name' => 'Stockholm', 'multiplier' => 1.20],
                    ['name' => 'Solna', 'multiplier' => 1.15],
                    ['name' => 'Sundbyberg', 'multiplier' => 1.15],
                    ['name' => 'Nacka', 'multiplier' => 1.10],
                    ['name' => 'Huddinge', 'multiplier' => 1.10],
                ],
            ],
            [
                'name' => 'Västra Götaland',
                'slug' => 'vastra-gotaland',
                'description' => 'Göteborg och Västra Götalands län',
                'status' => 'active',
                'cities' => [
                    ['name' => 'Göteborg', 'multiplier' => 1.15],
                    ['name' => 'Mölndal', 'multiplier' => 1.10],
                    ['name' => 'Borås', 'multiplier' => 1.05],
                    ['name' => 'Trollhättan', 'multiplier' => 1.00],
                ],
            ],
            [
                'name' => 'Skåne',
                'slug' => 'skane',
                'description' => 'Skåne län',
                'status' => 'active',
                'cities' => [
                    ['name' => 'Malmö', 'multiplier' => 1.15],
                    ['name' => 'Lund', 'multiplier' => 1.10],
                    ['name' => 'Helsingborg', 'multiplier' => 1.10],
                    ['name' => 'Kristianstad', 'multiplier' => 1.00],
                ],
            ],
            [
                'name' => 'Uppsala län',
                'slug' => 'uppsala-lan',
                'description' => 'Uppsala län',
                'status' => 'active',
                'cities' => [
                    ['name' => 'Uppsala', 'multiplier' => 1.10],
                    ['name' => 'Enköping', 'multiplier' => 1.00],
                ],
            ],
        ];

        foreach ($zones as $zoneData) {
            $cities = $zoneData['cities'];
            unset($zoneData['cities']);

            $zone = Zone::create($zoneData);

            foreach ($cities as $cityData) {
                City::create([
                    'zone_id' => $zone->id,
                    'name' => $cityData['name'],
                    'slug' => Str::slug($cityData['name']),
                    'city_multiplier' => $cityData['multiplier'],
                    'status' => 'active',
                ]);
            }
        }
    }
}

