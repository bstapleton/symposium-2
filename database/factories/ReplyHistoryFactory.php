<?php

namespace Database\Factories;

use App\Models\ReplyHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History>
 */
class ReplyHistoryFactory extends Factory
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

    public function withParent(int $postId): Factory|ReplyHistoryFactory
    {
        return $this->state(function (array $attributes) use ($postId) {
            $parent = ReplyHistory::factory()->create(['post_id' => $postId]);
            return [
                'parent_id' => $parent->id,
            ];
        });
    }
}
