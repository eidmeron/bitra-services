<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\Company;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample companies
        $companies = [
            [
                'email' => 'stadbolaget@example.com',
                'company_email' => 'info@stadbolaget.se',
                'company_number' => '0701-234567',
                'company_org_number' => '556789-1234',
                'site' => 'https://stadbolaget.se',
                'status' => 'active',
                'services' => ['hemstadning', 'flyttstadning'],
                'cities' => ['Stockholm', 'Solna', 'Sundbyberg'],
            ],
            [
                'email' => 'vvsexperterna@example.com',
                'company_email' => 'kontakt@vvsexperterna.se',
                'company_number' => '0702-345678',
                'company_org_number' => '556890-2345',
                'site' => 'https://vvsexperterna.se',
                'status' => 'active',
                'services' => ['vvs-tjanster'],
                'cities' => ['Stockholm', 'Uppsala'],
            ],
            [
                'email' => 'goteborgstad@example.com',
                'company_email' => 'info@goteborgstad.se',
                'company_number' => '0703-456789',
                'company_org_number' => '556901-3456',
                'site' => null,
                'status' => 'active',
                'services' => ['hemstadning'],
                'cities' => ['Göteborg', 'Mölndal', 'Borås'],
            ],
            [
                'email' => 'malningsgruppen@example.com',
                'company_email' => 'bokning@malningsgruppen.se',
                'company_number' => '0704-567890',
                'company_org_number' => '557012-4567',
                'site' => 'https://malningsgruppen.se',
                'status' => 'active',
                'services' => ['malning'],
                'cities' => ['Stockholm', 'Göteborg', 'Malmö'],
            ],
            [
                'email' => 'tradgardstjanst@example.com',
                'company_email' => 'info@tradgardstjanst.se',
                'company_number' => '0705-678901',
                'company_org_number' => '557123-5678',
                'site' => null,
                'status' => 'pending',
                'services' => ['grasklippning'],
                'cities' => ['Uppsala', 'Enköping'],
            ],
        ];

        foreach ($companies as $companyData) {
            // Create user for company
            $user = User::create([
                'type' => 'company',
                'email' => $companyData['email'],
                'password' => Hash::make('password'),
                'status' => 'active',
            ]);

            // Create company
            $company = Company::create([
                'user_id' => $user->id,
                'company_email' => $companyData['company_email'],
                'company_number' => $companyData['company_number'],
                'company_org_number' => $companyData['company_org_number'],
                'site' => $companyData['site'],
                'status' => $companyData['status'],
                'review_average' => $companyData['status'] === 'active' ? fake()->randomFloat(2, 4.0, 5.0) : 0,
                'review_count' => $companyData['status'] === 'active' ? fake()->numberBetween(5, 30) : 0,
            ]);

            // Attach services
            foreach ($companyData['services'] as $serviceSlug) {
                $service = Service::where('slug', $serviceSlug)->first();
                if ($service) {
                    $company->services()->attach($service->id);
                }
            }

            // Attach cities
            foreach ($companyData['cities'] as $cityName) {
                $city = City::where('name', $cityName)->first();
                if ($city) {
                    $company->cities()->attach($city->id);
                }
            }
        }

        $this->command->info('Created ' . count($companies) . ' companies');
    }
}

