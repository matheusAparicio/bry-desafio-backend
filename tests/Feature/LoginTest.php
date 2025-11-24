<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'incorrect'
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Unauthorized'
                 ]);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        // Create user with known credentials
        $user = User::factory()->create([
            'email' => 'valid@example.com',
            'password' => Hash::make('password123')
        ]);

        // Login
        $response = $this->postJson('/api/login', [
            'email' => 'valid@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token',
                     'token_type',
                     'expires_in'
                 ]);
    }
}
