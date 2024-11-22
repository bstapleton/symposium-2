<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'text' => $this->faker->paragraph(),
            'created_at' => now(),
        ];
    }

    public function withParent(int $postHistoryId): Factory|ReplyFactory
    {
        return $this->state(function (array $attributes) use ($postHistoryId) {
            $parent = Reply::factory()->create(['post_history_id' => $postHistoryId]);
            return [
                'parent_id' => $parent->id,
            ];
        });
    }
}
