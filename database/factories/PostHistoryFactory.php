<?php

namespace Database\Factories;

use App\Models\PostHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\History>
 */
class PostHistoryFactory extends Factory
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

    /**
     * Create a revision of a post's old content
     *
     * @param int $postId
     * @return Factory|PostHistoryFactory
     */
    public function withParent(int $postId): Factory|PostHistoryFactory
    {
        return $this->state(function (array $attributes) use ($postId) {
            $parent = PostHistory::factory()->create(['post_id' => $postId]);
            return [
                'parent_id' => $parent->id,
            ];
        });
    }
}
