<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'recipient_id' => $this->faker->randomElement([User::factory(), null]), 
            'content' => $this->faker->text(200), 
            'is_seen' => $this->faker->boolean(50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
