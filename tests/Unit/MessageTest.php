<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Message;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_retrieve_message_history()
    {
        $message1 = Message::factory()->create(['sender_id' => $this->user->id]);
        $message2 = Message::factory()->create(['recipient_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->getJson('/api/messages');

        $response->assertStatus(200)
                 ->assertJsonCount(2) // We created 2 messages
                 ->assertJsonFragment(['id' => $message1->id])
                 ->assertJsonFragment(['id' => $message2->id]);
    }

    public function test_can_send_a_new_message()
    {
        $recipient = User::factory()->create();

        $payload = [
            'recipient_id' => $recipient->id,
            'content' => 'Hello, this is a test message!'
        ];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/messages', $payload);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'data' => ['id', 'sender_id', 'recipient_id', 'content']])
                 ->assertJsonFragment(['content' => 'Hello, this is a test message!']);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->user->id,
            'recipient_id' => $recipient->id,
            'content' => 'Hello, this is a test message!',
        ]);
    }

    public function test_can_mark_messages_as_seen()
    {
        $message = Message::factory()->create(['recipient_id' => $this->user->id, 'is_seen' => false]);
        $payload = ['message_ids' => [$message->id]];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/messages/seen', $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Messages marked as seen']);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'is_seen' => true,
        ]);
    }

   
}
