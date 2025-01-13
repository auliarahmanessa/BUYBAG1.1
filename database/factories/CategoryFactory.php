<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word(); // Menggunakan word() untuk nama kategori
        return [
            'name' => $name,
            'status' => rand(0, 1), // Menambahkan status dengan nilai acak 0 atau 1
            'slug' => Str::slug($name), // Membuat slug dari nama
        ];
    }
}
