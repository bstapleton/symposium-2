<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostRevision;
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

    public function withParentReply(int $replyId): Factory|ReplyFactory
    {
        return $this->state(function (array $attributes) use ($replyId) {
            return [
                'replyable_id' => $replyId,
                'replyable_type' => Reply::class,
                'parent_id' => $replyId,
            ];
        });
    }

    public function withParentRevision(int $revisionId): Factory|ReplyFactory
    {
        return $this->state(function (array $attributes) use ($revisionId) {
            return [
                'replyable_id' => $revisionId,
                'replyable_type' => PostRevision::class
            ];
        });
    }

    public function withParentPost(int $postId): Factory|ReplyFactory
    {
        return $this->state(function (array $attributes) use ($postId) {
            return [
                'replyable_id' => $postId,
                'replyable_type' => Post::class
            ];
        });
    }
}
