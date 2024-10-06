<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        $data = [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'username', 'email'],
                'message',
                'token',
            ]);

        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
        $user = User::where('email', 'testuser@example.com')->first();
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_registration_fails_with_existing_email()
    {
        User::factory()->create(['email' => 'testuser@example.com']);

        $data = [
            'name' => 'newuser',
            'email' => 'testuser@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }
}
