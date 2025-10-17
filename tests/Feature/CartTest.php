<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_product_to_cart()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create([
            'price' => 100,
            'quantity' => 10,
        ]);

        $response = $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Assuming your controller redirects after adding to cart
        $response->assertStatus(302); 
        $response->assertSessionHas('cart');

        $cart = session('cart');
        $this->assertArrayHasKey($product->id, $cart);
        $this->assertEquals(2, $cart[$product->id]['quantity']);
        $this->assertEquals($product->price, $cart[$product->id]['price']);
    }

    /** @test */
    public function user_can_update_cart_quantity()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_remove_item_from_cart()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $product = Product::factory()->create();

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post('/cart/remove', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200);
    }
}
