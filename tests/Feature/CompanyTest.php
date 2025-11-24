<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_companies_when_authenticated()
    {
        // Create user
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        // Login and get token
        $login = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $login->assertStatus(200);
        $token = $login->json('token');

        // Create companies
        Company::factory()->count(3)->create();

        // JWT Auth
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/companies');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'cnpj',
                            'address',
                        ]
                    ]
                ]);
    }
}
