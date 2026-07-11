<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_register(): void 
    {
        $response = $this->postJson('/api/register', [
            'name'    => 'Dmytro',
            'email'   => 'dmytro@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['token', 'user']);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email'    => 'dmytro@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'dmytro@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user']);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'dmytro@test.com',
        ]);

        $response = $this->postJson('api/login', [
            'email'    => 'dmytro@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }
}
