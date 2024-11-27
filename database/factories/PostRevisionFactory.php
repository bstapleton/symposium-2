<?php

namespace Database\Factories;

use App\Models\PostRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Revision>
 */
class PostRevisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'created_at' => now(),
            'title' => $faker->sentence(),
            'text' => $faker->paragraph(),
        ];
    }

    /**
     * Create a revision where only the title has changed
     *
     * @param int $postId
     * @param int $userId
     * @return PostRevisionFactory|Factory
     */
    public function onlyTitle(int $postId, int $userId): PostRevisionFactory|Factory
    {
        return $this->state(function (array $attributes) use ($postId, $userId) {
            return [
                'post_id' => $postId,
                'user_id' => $userId,
                'title' => $this->faker->sentence(),
                'text' => null,
            ];
        });
    }

    /**
     * Create a revision where only the text has changed
     *
     * @param int $postId
     * @param int $userId
     * @return PostRevisionFactory|Factory
     */
    public function onlyText(int $postId, int $userId): PostRevisionFactory|Factory
    {
        return $this->state(function (array $attributes) use ($postId, $userId) {
            return [
                'post_id' => $postId,
                'user_id' => $userId,
                'title' => null,
                'text' => $this->faker->paragraph(),
            ];
        });
    }
}
