<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/user/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // Controller redirects to login after registration
        $response->assertRedirect(route('login'));

        // Ensure user exists in database
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // Email should NOT be verified yet
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNull($user->email_verified_at);
    }

    /** @test */
    public function verified_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'verified@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(), // mark as verified
        ]);

        $response = $this->post('/user/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Controller redirects to home after login
        $response->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->post('/user/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
