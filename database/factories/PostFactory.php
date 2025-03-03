<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageId = fake()->numberBetween(1, 1084);

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'image_url' => "https://picsum.photos/id/{$imageId}/1200/800",
            'description' => fake()->paragraph(),
            'likes' => fake()->numberBetween(0, 1000),
            'status' => fake()->randomElement(PostStatus::cases()),
        ];
    }
}
