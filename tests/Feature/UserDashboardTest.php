<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_complete_orders_table_on_dashboard()
    {
        $user = User::factory()->create([
            'first_name' => 'Dashboard',
            'last_name' => 'User',
            'email_verified_at' => now(),
        ]);

        $orders = Order::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
             ->get('/dashboard')
             ->assertStatus(200)
             ->assertSee($orders[0]->order_number)
             ->assertSee($orders[1]->order_number)
             ->assertSee($orders[2]->order_number);
    }

    /** @test */
    public function it_shows_track_your_order_link_correctly_on_dashboard()
    {
        $user = User::factory()->create([
            'first_name' => 'Dashboard',
            'last_name' => 'User',
            'email_verified_at' => now(),
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
             ->get('/dashboard')
             ->assertStatus(200)
             ->assertSee("/orders/{$order->id}/track");
    }

    /** @test */
    public function it_displays_order_details_with_items_history_and_progress()
    {
        $user = User::factory()->create([
            'first_name' => 'Dashboard',
            'last_name' => 'User',
            'email_verified_at' => now(),
        ]);

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
             ->get("/orders/{$order->id}")
             ->assertStatus(200)
             ->assertSee($order->order_number)
             ->assertSee('Order History');
    }
}
