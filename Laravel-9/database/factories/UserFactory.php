<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Assuming you want a common default password
            'approved' => $this->faker->boolean,
            'taxable' => $this->faker->boolean,
            'banned' => $this->faker->boolean,
            'email_verified' => $this->faker->boolean,
            'banned_reason' => $this->faker->sentence,
            'balance' => $this->faker->randomFloat(2, 0, 1000),
            'user_group_id' => UserGroup::inRandomOrder()->first(),
            'remember_token' => Str::random(10),
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
}
