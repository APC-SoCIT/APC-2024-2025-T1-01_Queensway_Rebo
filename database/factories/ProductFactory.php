<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'sku' => strtoupper($this->faker->bothify('??#####')),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => $this->faker->imageUrl(200, 200),
            'description' => $this->faker->sentence(),
            'quantity' => $this->faker->numberBetween(1, 100),

        ];
    }
}
