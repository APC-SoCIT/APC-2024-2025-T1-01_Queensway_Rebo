<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'order_number' => strtoupper($this->faker->bothify('ORD-#####')),
            'status' => 'pending',
            'total' => 0,
        ];
    }
}
