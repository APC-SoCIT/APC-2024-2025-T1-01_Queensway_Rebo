<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_checkout_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/checkout');

        $response->assertStatus(200);
        $response->assertViewIs('website.checkout');
    }

    /** @test */
    public function user_can_complete_checkout()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 100,
            'quantity' => 10,
        ]);

        // Build payload exactly like the controller expects
        $payload = [
            'items' => [
                [
                    'id' => $product->id,
                    'price' => $product->price,
                    'quantity' => 2,
                ]
            ],
            'totalAmount' => $product->price * 2,
            'orderID' => 'TESTORDER123',
            'shipping' => [
                'name' => $user->first_name . ' ' . $user->last_name,
                'address_line_1' => '123 Test Street',
                'address_line_2' => '',
                'city' => 'Test City',
                'state' => 'Test State',
                'postal_code' => '12345',
                'country' => 'Test Country',
            ]
        ];

        $response = $this->actingAs($user)->post('/checkout/complete', $payload);

        // Controller returns JSON 200 on success
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert order is created in database
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => $product->price * 2,
            'paypal_order_id' => 'TESTORDER123',
        ]);

        // Assert order item is created
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'unit_price' => $product->price,
        ]);

        // Assert product quantity is reduced
        $this->assertEquals(8, $product->fresh()->quantity);
    }
}
