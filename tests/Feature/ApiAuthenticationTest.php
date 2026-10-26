<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_surat_endpoint_returns_json_401_for_an_invalid_bearer_token(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->get('/api/surat');

        $response->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthenticated.']);
    }

    public function test_surat_endpoint_accepts_a_sanctum_bearer_token(): void
    {
        $user = User::factory()->create([
            'role' => 'staff',
            'is_active' => true,
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->get('/api/surat');

        $response->assertOk()
            ->assertJsonStructure(['current_page', 'data', 'per_page']);
    }
}
