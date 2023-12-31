<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
            'tg_id' => $this->faker->randomNumber(5),
            'username' => $this->faker->name(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $from->last_name ?? null,
            'language' => 'ru',
            'role_id' => 2,
            'mail' => 'random_mail@ya.ru',
            'number' => $this->faker->phoneNumber(),
            'is_premium' => false,
            'is_blocked' => false,
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
