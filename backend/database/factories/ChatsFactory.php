<?php

namespace Database\Factories;

use App\Models\Chats;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chats>
 */
class ChatsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'message' => fake()->sentence(),
        ];
    }
}
