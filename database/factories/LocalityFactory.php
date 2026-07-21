<?php

namespace Database\Factories;

use App\Enums\LocalityType;
use App\Models\County;
use App\Models\Locality;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Locality>
 */
class LocalityFactory extends Factory
{
    protected $model = Locality::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'siruta_code' => fake()->unique()->numberBetween(1000, 999999),
            'siruta_parent' => null,
            'county_id' => County::factory(),
            'name' => $name,
            'name_ascii' => Str::ascii($name),
            'type' => fake()->randomElement(LocalityType::cases())->value,
            'postal_code' => fake()->numerify('######'),
            'lat' => fake()->latitude(43.6, 48.3),
            'lng' => fake()->longitude(20.2, 29.7),
        ];
    }
}
