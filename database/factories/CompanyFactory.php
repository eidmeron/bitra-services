<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->company(),
            'company_logo' => null,
            'company_email' => fake()->companyEmail(),
            'company_number' => fake()->numerify('####-######'),
            'company_org_number' => fake()->unique()->numerify('######-####'),
            'site' => fake()->optional()->url(),
            'review_average' => fake()->randomFloat(2, 3, 5),
            'review_count' => fake()->numberBetween(0, 50),
            'status' => fake()->randomElement(['active', 'inactive', 'pending']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'review_average' => 0,
            'review_count' => 0,
        ]);
    }
}

