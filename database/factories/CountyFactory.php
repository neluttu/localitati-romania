<?php

namespace Database\Factories;

use App\Enums\DevelopmentRegion;
use App\Models\County;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\County>
 */
class CountyFactory extends Factory
{
    protected $model = County::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->city();
        $abbr = strtoupper(substr($name, 0, 2));

        return [
            'siruta_code' => fake()->unique()->numberBetween(10, 520),
            'name' => $name,
            'name_ascii' => Str::ascii($name),
            'slug' => Str::slug($name),
            'abbr' => $abbr,
            'region' => fake()->randomElement(DevelopmentRegion::cases())->value,
        ];
    }
}
