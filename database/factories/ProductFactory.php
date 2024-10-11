<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $random_value = $this->faker->numberBetween(1, 7);

        $label = '';
        if (in_array($random_value, [3, 5])) {
            $label = 'new';
        } elseif (in_array($random_value, [6, 7])) {
            $label = 'sale';
        }

        $name = rtrim($this->faker->sentence(2), '.');

        return [
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'image'       => 'images/' . $this->faker->numberBetween(1, 5) . '.jpeg',
            'weight'      => $this->faker->numberBetween(140, 240),
            'calories'    => $this->faker->randomFloat(1,220, 360),
            'price'       => $this->faker->numberBetween(180, 330),
            'label'       => $label ?? null,
            'active'      => $this->faker->numberBetween(1, 9) !== 1,
        ];
    }
}
