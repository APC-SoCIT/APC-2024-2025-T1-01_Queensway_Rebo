<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function user_can_view_order_details()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending', // required field
        ]);

        $response = $this->actingAs($this->user)->get(route('orders.show', $order));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_view_invoice()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)->get(route('order.invoice', $order));

        $response->assertStatus(200);
    }
}
