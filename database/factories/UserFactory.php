<?php

namespace Database\Factories;

use App\Enums\FeatureFlag;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => Role::USER->value,
            'feature_flag' => FeatureFlag::NONE->value,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function revisionSystem(): static
    {
        return $this->state(fn (array $attributes) => [
            'feature_flag' => FeatureFlag::REVISIONS_SYSTEM->value,
        ]);
    }

    public function moderator(): static
    {
        // Moderators are also users
        return $this->state(fn (array $attributes) => [
            'role' => Role::MODERATOR->value + Role::USER->value,
        ]);
    }

    public function admin(): static
    {
        // Admins are all the things
        return $this->state(fn (array $attributes) => [
            'role' => Role::ADMIN->value + Role::MODERATOR->value + Role::USER->value,
        ]);
    }
}
