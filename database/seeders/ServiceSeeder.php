<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stadningCategory = Category::where('slug', 'stadning')->first();
        $hantverkareCategory = Category::where('slug', 'hantverkare')->first();
        $tradgardCategory = Category::where('slug', 'tradgard')->first();

        $services = [];

        if ($stadningCategory) {
            $services[] = [
                'category_id' => $stadningCategory->id,
                'name' => 'Hemstädning',
                'slug' => 'hemstadning',
                'description' => 'Professionell hemstädning med ROT-avdrag',
                'base_price' => 500.00,
                'discount_percent' => 0,
                'one_time_booking' => true,
                'subscription_booking' => true,
                'rot_eligible' => true,
                'rot_percent' => 30.00,
                'status' => 'active',
            ];

            $services[] = [
                'category_id' => $stadningCategory->id,
                'name' => 'Flyttstädning',
                'slug' => 'flyttstadning',
                'description' => 'Flyttstädning enligt Folksams standard',
                'base_price' => 2500.00,
                'discount_percent' => 0,
                'one_time_booking' => true,
                'subscription_booking' => false,
                'rot_eligible' => false,
                'rot_percent' => 0,
                'status' => 'active',
            ];
        }

        if ($hantverkareCategory) {
            $services[] = [
                'category_id' => $hantverkareCategory->id,
                'name' => 'VVS-tjänster',
                'slug' => 'vvs-tjanster',
                'description' => 'Alla typer av VVS-arbeten med ROT-avdrag',
                'base_price' => 800.00,
                'discount_percent' => 0,
                'one_time_booking' => true,
                'subscription_booking' => false,
                'rot_eligible' => true,
                'rot_percent' => 30.00,
                'status' => 'active',
            ];

            $services[] = [
                'category_id' => $hantverkareCategory->id,
                'name' => 'Målning',
                'slug' => 'malning',
                'description' => 'Målning inne och ute med ROT-avdrag',
                'base_price' => 600.00,
                'discount_percent' => 0,
                'one_time_booking' => true,
                'subscription_booking' => false,
                'rot_eligible' => true,
                'rot_percent' => 30.00,
                'status' => 'active',
            ];
        }

        if ($tradgardCategory) {
            $services[] = [
                'category_id' => $tradgardCategory->id,
                'name' => 'Gräsklippning',
                'slug' => 'grasklippning',
                'description' => 'Regelbunden gräsklippning med ROT-avdrag',
                'base_price' => 400.00,
                'discount_percent' => 10.00,
                'one_time_booking' => true,
                'subscription_booking' => true,
                'rot_eligible' => true,
                'rot_percent' => 30.00,
                'status' => 'active',
            ];
        }

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}

