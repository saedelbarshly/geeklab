<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout_successfully()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Successfully logged out 🫡',
            ]);
    }

    public function test_logout_fails_if_user_not_authenticated()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
