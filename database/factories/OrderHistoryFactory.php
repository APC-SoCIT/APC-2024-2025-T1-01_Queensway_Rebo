<?php

namespace Database\Factories;

use App\Models\OrderHistory;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderHistoryFactory extends Factory
{
    protected $model = OrderHistory::class;

    public function definition()
    {
        return [
            'order_id'   => Order::factory(),
            'status'     => 'Paid',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
